<?php


namespace Benchmark\Metrics;


class MetricResult
{
	/** @var string */
	private $name;

	/** @var int */
	private $size;

	/** @var float[] */
	private $serialize = [];

	/** @var float[] */
	private $deserialize = [];

	/**
	 * @param float $time
	 */
	public function addSerialize($time) {

		$this->serialize[] = $time;
	}

	/**
	 * @param float $time
	 */
	public function addDeserialize($time) {

		$this->deserialize[] = $time;
	}

	/**
	 * @param float[] $numbers
	 * @return float
	 */
	private function computeMean($numbers) {

		$count = count($numbers);
		if($count == 0) return 0;
		$sum = 0;
		foreach ($numbers as $number) {
			$sum += $number;
		}
		return $sum / $count;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return int
	 */
	public function getSize()
	{
		return $this->size;
	}

	/**
	 * @param int $size
	 */
	public function setSize($size)
	{
		$this->size = $size;
	}

	/**
	 * @param bool $mean
	 * @return float[]|float
	 */
	public function getSerialize($mean = TRUE)
	{
		if($mean) {
			return $this->computeMean($this->serialize);
		}
		return $this->serialize;
	}

	/**
	 * @param float[] $serialize
	 */
	public function setSerialize(array $serialize)
	{
		$this->serialize = $serialize;
	}

	/**
	 * @param bool $mean
	 * @return float[]|float
	 */
	public function getDeserialize($mean = TRUE)
	{
		if($mean) {
			return $this->computeMean($this->deserialize);
		}
		return $this->deserialize;
	}

	/**
	 * @param float[] $deserialize
	 */
	public function setDeserialize(array $deserialize)
	{
		$this->deserialize = $deserialize;
	}


}