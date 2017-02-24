<?php

namespace Benchmark\Metrics;


use Benchmark\Config;

interface IMetric
{
	const OUTER_REPETITION = 30;

	/**
	 * @return Info
	 */
	public function getInfo();

	/**
	 * @param mixed $data
	 * @param string $dataFile
	 * @param int $inner
	 * @param int $outer
	 * @return MetricResult
	 * @internal param int $repetitions
	 */
	public function run($data, $dataFile, $inner = Config::REPETITIONS_INNER, $outer = Config::REPETITIONS_OUTER);
}