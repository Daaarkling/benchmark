<?php

namespace Benchmark\Metrics\Json;


use Benchmark\Converters\JsonConverter;
use Benchmark\Metrics\AMetric;
use Seld\JsonLint\JsonParser;


class SeldJsonlint extends AMetric
{

	/** @var JsonParser */
	private $parser;


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