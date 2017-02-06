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

		// Serialization
		// Do it once to warm up.
		$encodedData = $this->serialize($data);
		if ($encodedData) {
			for ($j = 0; $j < IMetric::OUTER_REPETITION; $j++) {
				$start = microtime(TRUE);
				for ($i = 0; $i < $repetitions; $i++) {
					$this->serialize($data);
				}
				$time = microtime(TRUE) - $start;
				$result->addSerialize($time);
			}
			$size = strlen($encodedData);
			$result->setSize($size);
		} else {
			$encodedData = $this->prepareDataForDeserialize();
			if (!$encodedData) {
				return $result;
			}
		}

		// Deserialization
		// Do it once to warm up.
		$outputD = $this->deserialize($encodedData);
		if($outputD) {
			for ($j = 0; $j < IMetric::OUTER_REPETITION; $j++) {
				$start = microtime(TRUE);
				for ($i = 1; $i <= $repetitions; $i++) {
					$this->deserialize($encodedData);
				}
				$time = microtime(TRUE) - $start;
				$result->addDeserialize($time);
			}
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
	 * If serialize() method is not implemented this method must be otherwise deserialize() wont proceed
	 *
	 * @return mixed
	 */
	protected function prepareDataForDeserialize()
	{
		return NULL;
	}


	/**
	 * Method should return result of serialize if everything went well (result will be used for deserialize) or
	 * FALSE if serialize is not implemented, in that case method prepareDataForDeserialize() must be implemented
	 *
	 * @param mixed $data
	 * @return string|FALSE
	 */
	protected function serialize($data)
	{
		return FALSE;
	}


	/**
	 * Method should return TRUE if everything went well or
	 * FALSE if deserialize is not implemented
	 *
	 * @param mixed $data
	 * @return bool
	 */
	protected function deserialize($data)
	{
		return FALSE;
	}

}