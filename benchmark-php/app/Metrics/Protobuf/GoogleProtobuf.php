<?php

namespace Benchmark\Metrics\Protobuf;


use Benchmark\Config;
use Benchmark\Converters\GoogleProtobuf\PersonCollection;
use Benchmark\Converters\GoogleProtobuf\ProtobufConverter;
use Benchmark\Metrics\AMetric;
use Benchmark\Metrics\Info;


class GoogleProtobuf extends AMetric
{
	/** @var PersonCollection */
	private $personCollection;

	public function getInfo()
	{
		return new Info(Config::FORMAT_PROTOBUF, 'google/protobuf', 'https://github.com/google/protobuf', '3.1.0');
	}

	public function prepareBenchmark()
	{
		$this->personCollection = new PersonCollection();
	}

	protected function prepareDataForSerialize()
	{
		$converter = new ProtobufConverter();
		return $converter->convertData($this->data);
	}


	public function serialize($data)
	{
		return $data->serializeToString();
	}


	public function deserialize($data)
	{
		$data = $this->personCollection->mergeFromString($data);
		return TRUE;
	}
}