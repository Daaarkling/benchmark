<?php


namespace Benchmark;


use Benchmark\Converters\ArrayConverter;
use Benchmark\Metrics\IMetric;
use Benchmark\Output\IOutputHandler;

class Benchmark
{
	/** @var Config */
	protected $config;

	/** @var IOutputHandler */
	private $outputHandler;


	public function __construct(Config $config, IOutputHandler $outputHandler)
	{
		$this->config = $config;
		$this->outputHandler = $outputHandler;
	}


	/**
	 * Run benchmark
	 */
	public function run()
	{
		$result = [];
		$data = $this->prepareData();
		$dataFile = $this->config->getTestData();
		$inner = $this->config->getInner();
		$outer = $this->config->getOuter();

		foreach ($this->config->getMetrics() as $metric){


			// Run metric benchmark
			$metricResult = $metric->run($data, $dataFile, $inner, Config::REPETITIONS_OUTER, $outer);

			if($metricResult === NULL){
				continue;
			}

			$result[] = $metricResult;
		}


		$this->outputHandler->handleBenchmarkResult($result);
		$this->outputHandler->printBenchmarkInfo($this->getInfo());
	}


	/**
	 * @return array
	 */
	public function getInfo() {

		return [
			'PHP version' => phpversion(),
			'Test data size (raw)' => round(filesize($this->config->getTestData()) / 1024, 2) . ' kB',
			'Outer repetition' => $this->config->getOuter(),
			'Inner repetition' => $this->config->getInner(),
			'Date' => (new \DateTime())->format('c'),
		];
	}


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