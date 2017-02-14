<?php

namespace Benchmark\Metrics\Avro;


use AvroDataIOReader;
use AvroDataIOWriter;
use AvroIODatumReader;
use AvroStringIO;
use Benchmark\Config;
use Benchmark\Metrics\IMetric;
use Benchmark\Metrics\Info;
use Benchmark\Metrics\MetricResult;


class AvroOfficial implements IMetric
{

	public function getInfo()
	{
		return new Info(Config::FORMAT_AVRO, 'apache/avro', 'http://avro.apache.org', '1.8.1');
	}


	public function run($data, $dataFile, $repetitions = Config::REPETITIONS_DEFAULT)
	{
		$result = new MetricResult();
		$result->setInfo($this->getInfo());

		// Serialization
		// Do it once to warm up.
		$this->serialize($data);

		for ($j = 0; $j < IMetric::OUTER_REPETITION; $j++) {
			$resultSerialize = [];
			for ($i = 1; $i <= $repetitions; $i++) {
				$resultSerialize[] = $this->serialize($data);
			}

			$time = 0;
			foreach ($resultSerialize as $rS) {
				$time += $rS['time'];
			}

			$result->addSerialize($time);
			$result->setSize(strlen($rS['string']));


			$time = 0;
			for ($i = 1; $i <= $repetitions; $i++) {
				$time += $this->deserialize($rS['string']);
			}
			$result->addDeserialize($time);
		}
		return $result;
	}



	private function serialize($data)
	{
		$schema = file_get_contents(__DIR__ . '/../../Converters/Avro/avro_schema.json');
		$writerSchema = \AvroSchema::parse($schema);
		$writer = new \AvroIODatumWriter($writerSchema);
		$io = new AvroStringIO();
		$dataWriter = new AvroDataIOWriter($io, $writer, $writerSchema);

		$start = microtime(TRUE);
		$dataWriter->append($data);
		$dataWriter->close();
		$string = $io->string();
		$time = microtime(TRUE) - $start;

		return [
			'time' => $time,
			'string' => $string
		];
	}



	private function deserialize($data)
	{
		$readIO = new AvroStringIO($data);

		$start = microtime(TRUE);
		$dataReader = new AvroDataIOReader($readIO, new AvroIODatumReader());
		$data = $dataReader->data();
		$time = microtime(TRUE) - $start;

		return $time;
	}


}