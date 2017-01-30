<?php

namespace Benchmark\Metrics;


use Benchmark\Config;

interface IMetric
{
	/**
	 * Method for run the benchmark, should execute serialize() and deserialize() methods and deliver result
	 *
	 * @param mixed $data
	 * @param string $dataFile
	 * @param int $repetitions
	 * @return MetricResult
	 */
	public function run($data, $dataFile, $repetitions = Config::REPETITIONS_DEFAULT);


	/**
	 * Method returns string if everything went well (result may be used for deserialize) or
	 * FALSE if serialize is not implemented
	 *
	 *
	 * @param mixed $data
	 * @return string|FALSE
	 */
	public function serialize($data);


	/**
	 * Method returns TRUE if everything went well or
	 * FALSE if deserialize is not implemented
	 *
	 *
	 * @param mixed $data
	 * @return bool
	 */
	public function deserialize($data);
}