<?php


namespace Benchmark;


class Config
{
	const FORMATS = ['native', 'json', 'xml', 'avro', 'msgpack', 'protobuf'];
	const REPETITIONS_DEFAULT = 100;

	/** @var string */
	public static $configFile = __DIR__ . '/../config/config.json';

	/** @var string */
	public static $schemaFile = __DIR__ . '/../config/schema.json';

	/** @var string */
	public static $testDataFile = __DIR__ . '/../config/test_data_small.json';

	/** @var \stdClass */
	private $configNode;

	/** @var string */
	private $testData;

	/** @var int */
	private $repetitions = self::REPETITIONS_DEFAULT;

	/** @var null|string */
	private $format;

	/**
	 * Config constructor.
	 * @param \stdClass $configNode
	 * @param string $testData
	 * @param int $repetitions
	 * @param string $format
	 */
	public function __construct($configNode, $testData, $repetitions = self::REPETITIONS_DEFAULT, $format = NULL)
	{
		$this->configNode = $configNode;
		$this->testData = $testData;
		$this->repetitions = $repetitions;
		$this->format = $format;
	}

	/**
	 * @return \stdClass
	 */
	public function getConfigNode()
	{
		if ($this->format !== NULL) {
			foreach ($this->configNode as $formatName => $libs) {
				if ($formatName === $this->format) {
					$stdClass = new \stdClass();
					$stdClass->$formatName = $libs;
					return $stdClass;
				}
			}
		}
		return $this->configNode;
	}

	/**
	 * @param \stdClass $configNode
	 */
	public function setConfigNode($configNode)
	{
		$this->configNode = $configNode;
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
	public function getRepetitions()
	{
		return $this->repetitions;
	}

	/**
	 * @param int $repetitions
	 */
	public function setRepetitions($repetitions)
	{
		if ($repetitions >= 1) {
			$this->repetitions = $repetitions;
		} else {
			$this->repetitions = self::REPETITIONS_DEFAULT;
		}
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