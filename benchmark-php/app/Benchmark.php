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


		$rows = $this->transformResult($result);
		$headers = ["Name", "Serialize (ms)", "Deserialize (ms)", "Size (kB)"];
		$this->handleResult($headers, $rows);
	}


	/**
	 * @param array $result
	 * @return array
	 */
	protected function transformResult($result)
	{
		$rows = [];
		foreach ($result as $typeName => $libs) {
			foreach ($libs as $metricResult) {
				$rows[] = [
					$typeName . ' - ' . $metricResult->getName(),
					$metricResult->getSerialize() !== NULL ? round($metricResult->getSerialize() * 1000, 4) : 0,
					$metricResult->getDeserialize() !== NULL ? round($metricResult->getDeserialize() * 1000, 4) : 0,
					$metricResult->getSerialize() !== NULL ? round($metricResult->getSize() / 1024, 4) : 0,
				];
			}
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