<?php

namespace Benchmark\Metrics\Protobuf;


use Benchmark\Converters\GoogleProtobuf\PersonCollection;
use Benchmark\Converters\GoogleProtobuf\ProtobufConverter;
use Benchmark\Metrics\AMetric;


class GoogleProtobuf extends AMetric
{
	/** @var PersonCollection */
	private $personCollection;


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
		return $data->encode();
	}


	public function deserialize($data)
	{
		$data = $this->personCollection->decode($data);
		return TRUE;
	}
}