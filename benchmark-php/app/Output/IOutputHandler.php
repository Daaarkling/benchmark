<?php

namespace Benchmark\Output;


use Benchmark\Metrics\MetricResult;

interface IOutputHandler
{

	/**
	 * @param MetricResult[] $result
	 */
	public function handleBenchmarkResult($result);


	/**
	 * @param array $benchmarkInfo
	 */
	public function printBenchmarkInfo($benchmarkInfo);

}