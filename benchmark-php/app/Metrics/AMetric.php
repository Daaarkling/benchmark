<?php

namespace Benchmark\Metrics;


use Benchmark\Config;

abstract class AMetric implements IMetric
{

	/** @var  mixed */
	protected $data;

	/** @var  string */
	protected $dataFile;


	/**
	 * @param mixed $data
	 * @param string $dataFile
	 * @param int $inner
	 * @param int $outer
	 * @return MetricResult
	 */
	public function run($data, $dataFile, $inner = Config::REPETITIONS_INNER, $outer = Config::REPETITIONS_OUTER)
	{
		// Prepare
		$this->data = $data;
		$this->dataFile = $dataFile;
		$result = new MetricResult();
		$result->setInfo($this->getInfo());
		$this->prepareBenchmark();
		$data = $this->prepareDataForSerialize();

		// Serialization
		// Do it once to warm up.
		$encodedData = $this->serialize($data);
		if ($encodedData) {
			for ($j = 0; $j < $outer; $j++) {
				$start = microtime(TRUE);
				for ($i = 0; $i < $inner; $i++) {
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
			for ($j = 0; $j < $outer; $j++) {
				$start = microtime(TRUE);
				for ($i = 1; $i <= $inner; $i++) {
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