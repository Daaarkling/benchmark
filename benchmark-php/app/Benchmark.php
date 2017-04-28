<?php


namespace Benchmark;


use Becnhmark\Logger\ILogger;
use Benchmark\Converters\ArrayConverter;
use Benchmark\Output\IOutputHandler;

class Benchmark
{
	/** @var Config */
	protected $config;

	/** @var IOutputHandler */
	private $outputHandler;

	/** @var ILogger */
    private $logger;


    public function __construct(Config $config, IOutputHandler $outputHandler, ILogger $logger = NULL)
	{
		$this->config = $config;
		$this->outputHandler = $outputHandler;
        $this->logger = $logger;
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

            if ($this->logger) {
                $this->logger->startMessage($metric->getInfo());
            }

			// Run metric benchmark
			$metricResult = $metric->run($data, $dataFile, $inner, $outer);

			if ($metricResult === NULL){
				continue;
			}

			if ($this->logger) {
			    $this->logger->endMessage($metric->getInfo());
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