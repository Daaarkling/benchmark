<?php

namespace Benchmark\Metrics\Json;


use Benchmark\Config;
use Benchmark\Converters\JsonConverter;
use Benchmark\Metrics\AMetric;
use Benchmark\Metrics\Info;
use Seld\JsonLint\JsonParser;


class SeldJsonlint extends AMetric
{

	/** @var JsonParser */
	private $parser;

	public function getInfo()
	{
		return new Info(Config::FORMAT_JSON, 'seld/jsonlint', 'https://github.com/Seldaek/jsonlint', '1.5');
	}


	public function prepareBenchmark()
	{
		$this->parser = new JsonParser();
	}


	protected function prepareDataForDeserialize()
	{
		$jsonConverter = new JsonConverter();
		return $jsonConverter->convertData($this->data);
	}


	public function deserialize($data)
	{
		$data = $this->parser->parse($data);
		return TRUE;
	}
}