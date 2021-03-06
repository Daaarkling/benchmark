<?php


namespace Benchmark;


use Benchmark\Metrics\IMetric;
use Benchmark\Utils\ClassLoader;

class Config
{
	const FORMAT_AVRO = 'avro';
	const FORMAT_JSON = 'json';
	const FORMAT_XML = 'xml';
	const FORMAT_MSGPACK = 'msgpack';
	const FORMAT_PROTOBUF = 'protobuf';
	const FORMAT_NATIVE = 'native';
	const FORMATS = [self::FORMAT_NATIVE, self::FORMAT_JSON, self::FORMAT_XML, self::FORMAT_AVRO, self::FORMAT_MSGPACK, self::FORMAT_PROTOBUF];

	const REPETITIONS_INNER = 100;
	const REPETITIONS_OUTER = 30;

	/** @var string */
	public static $testDataFile = __DIR__ . '/../testdata/test_data_small.json';

	/** @var IMetric[] */
	private $metrics;

	/** @var string */
	private $testData;

	/** @var int */
	private $inner = self::REPETITIONS_INNER;

	/** @var int */
	private $outer = self::REPETITIONS_OUTER;

	/** @var null|string */
	private $format;



	/**
	 * Config constructor.
	 * @param string $testData
	 * @param int $inner
	 * @param int $outer
	 * @param string $format
	 */
	public function __construct($testData, $inner = self::REPETITIONS_INNER, $outer = self::REPETITIONS_OUTER, $format = NULL)
	{
		$this->testData = $testData;
		$this->inner = $inner;
		$this->outer = $outer;
		$this->format = $format;
		$this->findMetrics();
	}



	private function findMetrics() {

		$this->metrics = ClassLoader::loadClasses();
		if ($this->format) {
			$this->metrics = array_filter($this->metrics, function ($metric) {
				return $metric->getInfo()->getFormat() == $this->format;
			});
		}
	}


	/**
	 * @return IMetric[]
	 */
	public function getMetrics()
	{
		return $this->metrics;
	}

	/**
	 * @param IMetric[] $metrics
	 */
	public function setMetrics($metrics)
	{
		$this->metrics = $metrics;
	}

	/**
	 * @return string
	 */
	public function getTestData()
	{
		return $this->testData;
	}

	/**
	 * @param string $testData
	 */
	public function setTestData($testData)
	{
		$this->testData = $testData;
	}

	/**
	 * @return int
	 */
	public function getInner()
	{
		return $this->inner;
	}

	/**
	 * @param int $inner
	 */
	public function setInner($inner)
	{
		if ($inner >= 1) {
			$this->inner = $inner;
		} else {
			$this->inner = self::REPETITIONS_INNER;
		}
	}

	/**
	 * @return int
	 */
	public function getOuter()
	{
		return $this->outer;
	}

	/**
	 * @param int $outer
	 */
	public function setOuter($outer)
	{
		$this->outer = $outer;
	}



	/**
	 * @return null|string
	 */
	public function getFormat()
	{
		return $this->format;
	}

	/**
	 * @param null|string $format
	 */
	public function setFormat($format)
	{
		if (in_array($format, self::FORMATS)) {
			$this->format = $format;
		} else {
			$this->format = NULL;
		}
	}
}