<?php

namespace Benchmark;


use League\Csv\Writer;


class BenchmarkCsvOutput extends Benchmark
{

	/** @var string */
	public static $fileName = 'php-benchmark-output.csv';

	/** @var string */
	private $outputDir;


	public function __construct(Config $config, $outputDir)
	{
		parent::__construct($config);
		$this->outputDir = $outputDir;
	}


	protected function transformResult($result)
	{
		$headers = ["Name", "Serialize (ms)", "Deserialize (ms)", "Size (B)"];
		$rows = [];

		foreach ($result as $typeName => $libs) {
			foreach ($libs as $metricResult) {
				$rows[] = [
					'php - ' . $typeName . ' - ' . $metricResult->getName(),
					round($metricResult->getSerialize() * 1000, 4),
					round($metricResult->getDeserialize() * 1000, 4),
					$metricResult->getSize(),
				];
			}
		}
		$this->handleResult($headers, $rows);
	}


	protected function handleResult($headers, $rows)
	{
		$headers = ["Name", "Serialize (ms)", "Deserialize (ms)", "Size (B)"];
		$name = $this->outputDir . '/' . self::$fileName;
		$csv = Writer::createFromPath($name, 'w');
		$csv->setDelimiter(';');
		$csv->insertOne($headers);
		$csv->insertAll($rows);
	}
}