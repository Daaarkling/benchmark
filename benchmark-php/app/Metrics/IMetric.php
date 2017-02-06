<?php

namespace Benchmark\Metrics;


use Benchmark\Config;

interface IMetric
{
	const OUTER_REPETITION = 10;

	/**
	 * @param mixed $data
	 * @param string $dataFile
	 * @param int $repetitions
	 * @return MetricResult
	 */
	public function run($data, $dataFile, $repetitions = Config::REPETITIONS_DEFAULT);
}