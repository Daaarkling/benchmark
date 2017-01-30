<?php


namespace Benchmark;


use Benchmark\Converters\ArrayConverter;
use Benchmark\Metrics\IMetric;
use Benchmark\Utils\ClassHelper;
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

		foreach ($this->config->getConfigNode() as $formatName => $libs){
			foreach ($libs as $lib){

				$className = $lib->class;
				if (!($class = ClassHelper::instantiateClass($className, IMetric::class))) {
					continue;
				}

				// Run metric benchmark
				$metricResult = $class->run($data, $dataFile, $repetitions);

				if($metricResult === NULL){
					continue;
				}

				$name = property_exists($lib, 'version') ? $lib->name . ' ' . $lib->version : $lib->name;
				$metricResult->setName($name);

				$result[$formatName][] = $metricResult;
			}
		}
		$this->transformResult($result);
	}



	protected function transformResult($result)
	{
		$headers = ["Name", "Serialize", "Deserialize", "Size"];
		$rows = [];

		foreach ($result as $typeName => $libs) {
			foreach ($libs as $metricResult) {
				$rows[] = [
					$typeName . ' - ' . $metricResult->getName(),
					$metricResult->getSerialize() !== NULL ? Formatters::seconds($metricResult->getSerialize()) : "---",
					$metricResult->getDeserialize() !== NULL ? Formatters::seconds($metricResult->getDeserialize()) : "---",
					$metricResult->getSerialize() !== NULL ? Formatters::bytes($metricResult->getSize()) : "---",
				];
			}
		}

		$this->handleResult($headers, $rows);
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