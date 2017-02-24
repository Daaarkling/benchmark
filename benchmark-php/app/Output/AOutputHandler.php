<?php

namespace Benchmark\Output;


use Benchmark\Metrics\MetricResult;


abstract class AOutputHandler implements IOutputHandler
{

	/** @var int */
	protected $outer;


	public function __construct($outer)
	{
		$this->outer = $outer;
	}

	public function handleBenchmarkResult($result)
	{
		// sort by name
		uasort($result, function ($a, $b) {
			return $a->getFullName() > $b->getFullName();
		});

		$this->transformResult($result);
		$this->transformResultSummarize($result);
	}


	/**
	 * @param MetricResult[] $result
	 */
	protected function transformResultSummarize($result)
	{
		$rows = [];
		foreach ($result as $metricResult) {
			$rows[] = [
				$metricResult->getFullName(),
				$metricResult->getSerialize() !== NULL ? round($metricResult->getSerialize() * 1000, 4) : 0,
				$metricResult->getDeserialize() !== NULL ? round($metricResult->getDeserialize() * 1000, 4) : 0,
				$metricResult->getSerialize() !== NULL ? round($metricResult->getSize() / 1024, 4) : 0,
			];
		}

		$headers = ["Name", "Serialize (ms)", "Deserialize (ms)", "Size (kB)"];
		$this->printOutput("php summarize", $headers, $rows);
	}

	/**
	 * @param MetricResult[] $result
	 */
	protected function transformResult($result)
	{
		$headersSerialize = [];
		$headersDeserialize = [];
		$rowsSerialize = [];
		$rowsDeserialize = [];

		for ($i = 0; $i < $this->outer; $i++) {
			foreach ($result as $metricResult) {
				if ($i === 0) {
					if (!$metricResult->isSerializeEmpty()) {
						$headersSerialize[] = $metricResult->getFullName();
					}
					if (!$metricResult->isDeserializeEmpty()) {
						$headersDeserialize[] = $metricResult->getFullName();
					}
				}
				$sizeSerialize = count($metricResult->getSerialize(FALSE));
				if ($sizeSerialize > 0 && $i < $sizeSerialize) {
					$rowsSerialize[$i][] = round($metricResult->getSerialize(FALSE)[$i] * 1000, 4);
				}
				$sizeDeserialize = count($metricResult->getDeserialize(FALSE));
				if ($sizeDeserialize > 0 && $i < $sizeDeserialize) {
					$rowsDeserialize[$i][] = round($metricResult->getDeserialize(FALSE)[$i] * 1000, 4);
				}
			}
		}

		$this->printOutput("php serialize", $headersSerialize, $rowsSerialize);
		$this->printOutput("php deserialize", $headersDeserialize, $rowsDeserialize);
	}



	/**
	 * @param string $name
	 * @param string[] $headers
	 * @param string[][] $rows
	 */
	protected abstract function printOutput($name, $headers, $rows);
}