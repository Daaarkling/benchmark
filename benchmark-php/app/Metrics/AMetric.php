<?php

namespace Benchmark\Metrics;


abstract class AMetric implements IMetric
{

	/** @var  mixed */
	protected $data;

	/** @var  string */
	protected $dataFile;


	/**
	 * @param mixed $data
	 * @param string $dataFile
	 * @param int $repetitions
	 * @return MetricResult
	 */
	public function run($data, $dataFile, $repetitions = 10)
	{
		// Prepare
		$this->data = $data;
		$this->dataFile = $dataFile;
		$result = new MetricResult();
		$this->prepareBenchmark();
		$data = $this->prepareDataForSerialize();
		$outputS = FALSE;
		$outputD = FALSE;


		// Do it once to warm up.
		$this->serialize($data);


		// Run serialization
		$start = microtime(TRUE);
		for ($i = 1; $i <= $repetitions; $i++) {
			$outputS = $this->serialize($data);
		}
		$time = microtime(TRUE) - $start;


		// Handle serialization result
		if ($outputS !== FALSE) {
			$result->setSerialize($time);
			$encodedData = $outputS;
			$size = strlen($encodedData);
			$result->setSize($size);
		} else {
			$encodedData = $this->prepareDataForDeserialize();
			if (!is_string($encodedData)) {
				return $result;
			}
		}

		// Do it once to warm up.
		$this->deserialize($encodedData);

		// Run deserialization
		$start = microtime(TRUE);
		for ($i = 1; $i <= $repetitions; $i++) {
			$outputD = $this->deserialize($encodedData);
		}
		$time = microtime(TRUE) - $start;


		// Handle deserialization result
		if ($outputD === TRUE) {
			$result->setDeserialize($time);
		}

		return $result;
	}



	/**
	 * Its called once before serialize()
	 */
	protected function prepareBenchmark()
	{

	}


	/**
	 * @return mixed
	 */
	protected function prepareDataForSerialize()
	{
		return $this->data;
	}


	/**
	 * If serialize() returns FALSE this method must return string, otherwise deserialize() wont proceed
	 *
	 * @return mixed
	 */
	protected function prepareDataForDeserialize()
	{
	}


	/**
	 * @param mixed $data
	 * @return string|FALSE
	 */
	public function serialize($data)
	{
		return FALSE;
	}


	/**
	 * @param mixed $data
	 * @return bool
	 */
	public function deserialize($data)
	{
		return FALSE;
	}

}