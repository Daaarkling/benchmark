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
		$rows = parent::transformResult($result);
		for ($i = 0; $i < count($rows); $i++) {
			$rows[$i][0] = 'php - ' . $rows[$i][0];
		}
		return $rows;
	}


	protected function handleResult($headers, $rows)
	{
		$name = $this->outputDir . '/' . self::$fileName;
		$csv = Writer::createFromPath($name, 'w');
		$csv->setDelimiter(';');
		$csv->insertOne($headers);
		$csv->insertAll($rows);
	}
}