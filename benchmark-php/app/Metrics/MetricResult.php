<?php


namespace Benchmark\Metrics;


class MetricResult
{
	/** @var string */
	private $name;

	/** @var int */
	private $size;

	/** @var float */
	private $serialize;

	/** @var float */
	private $deserialize;



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
	 * @return float
	 */
	public function getSerialize()
	{
		return $this->serialize;
	}

	/**
	 * @param float $serialize
	 */
	public function setSerialize($serialize)
	{
		$this->serialize = $serialize;
	}

	/**
	 * @return float
	 */
	public function getDeserialize()
	{
		return $this->deserialize;
	}

	/**
	 * @param float $deserialize
	 */
	public function setDeserialize($deserialize)
	{
		$this->deserialize = $deserialize;
	}


}