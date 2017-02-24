<?php

namespace Benchmark;


use Benchmark\Output\AOutputHandler;
use League\Csv\Writer;
use Nette\Utils\FileSystem;
use Nette\Utils\Strings;


class CsvOutput extends AOutputHandler
{
	/** @var string */
	private $outputDir;


	public function __construct($outer, $outputDir)
	{
		parent::__construct($outer);
		$this->outputDir = $outputDir;
	}


	protected function printOutput($name, $headers, $rows)
	{
		$name = $this->outputDir . '/' . Strings::webalize($name) . '.csv';
		$csv = Writer::createFromPath($name, 'w');
		$csv->setDelimiter(';');
		$csv->insertOne($headers);
		$csv->insertAll($rows);
	}

	public function printBenchmarkInfo($benchmarkInfo)
	{
		$string = "";
		foreach ($benchmarkInfo as $key => $value) {
			$string .= $key . ": " . $value . "\n";
		}
		FileSystem::write($this->outputDir . '/' . 'php-info.txt', $string);
	}


}