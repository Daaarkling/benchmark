<?php


namespace Benchmark;


use Benchmark\Converters\ArrayConverter;
use Benchmark\Metrics\IMetric;
use Benchmark\Metrics\MetricResult;
use Benchmark\Utils\ClassLoader;
use Benchmark\Utils\Formatters;

abstract class Benchmark
{
	/** @var Config */
	protected $config;



	public function __construct(Config $config)
	{
		$this->config = $config;
	}


	/**
	 * Run benchmark
	 */
	public function run()
	{
		$result = [];
		$data = $this->prepareData();
		$dataFile = $this->config->getTestData();
		$repetitions = $this->config->getRepetitions();

		foreach ($this->config->getMetrics() as $metric){


			// Run metric benchmark
			$metricResult = $metric->run($data, $dataFile, $repetitions);

			if($metricResult === NULL){
				continue;
			}

			$result[] = $metricResult;
		}


		$rows = $this->transformResult($result);
		$headers = ["Name", "Serialize (ms)", "Deserialize (ms)", "Size (kB)"];
		$this->handleResult($headers, $rows);
	}


	/**
	 * @param MetricResult[] $result
	 * @return array
	 */
	protected function transformResult($result)
	{
		$rows = [];
		foreach ($result as $metricResult) {
			$rows[] = [
				$metricResult->getFullName(),
				$metricResult->getSerialize() !== NULL ? round($metricResult->getSerialize() * 1000, 4) : 0,
				$metricResult->getDeserialize() !== NULL ? round($metricResult->getDeserialize() * 1000, 4) : 0,
				$metricResult->getSerialize() !== NULL ? round($metricResult->getSize() / 1024, 4) : 0,
			];
		}

		// sort by name
		uasort($rows, function ($a, $b) {
			return $a[0] > $b[0];
		});

		return $rows;
	}


	/**
	 * @param string[] $headers
	 * @param string[] $rows
	 */
	protected abstract function handleResult($headers, $rows);


	/**
	 * @return mixed
	 */
	protected function prepareData()
	{
		$arrayConverter = new ArrayConverter();
		$data = $arrayConverter->convertData(file_get_contents($this->config->getTestData()));
		return $data;
	}


	/**
	 * @return Config
	 */
	public function getConfig()
	{
		return $this->config;
	}

	/**
	 * @param Config $config
	 */
	public function setConfig($config)
	{
		$this->config = $config;
	}


}