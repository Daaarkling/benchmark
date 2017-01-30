<?php


namespace Benchmark\Converters\Avro;


use Benchmark\Converters\IDataConverter;
use Nette\Utils\Json;

class AvroConverter implements IDataConverter
{


	public function convertData($jsonTestData)
	{
		$arrayData = Json::decode($jsonTestData, Json::FORCE_ARRAY);

		$io = new \AvroStringIO();

		$schema = file_get_contents(__DIR__ . '/avro_schema.json');

		$writerSchema = \AvroSchema::parse($schema);
		$writer = new \AvroIODatumWriter($writerSchema);
		$dataWriter = new \AvroDataIOWriter($io, $writer, $writerSchema);

		$dataWriter->append($arrayData);
		$dataWriter->close();
		return $io->string();
	}


}