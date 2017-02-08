<?php

namespace Benchmark\Metrics\Protobuf;


use Benchmark\Converters\AllegroPhpProtobuf\PersonCollection;
use Benchmark\Converters\AllegroPhpProtobuf\ProtobufConverter;
use Benchmark\Metrics\AMetric;


class AllegroPhpProtobuf extends AMetric
{
	/** @var  PersonCollection */
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
		return $data->serializeToString();
	}


	public function deserialize($data)
	{
		try {
			$data = $this->personCollection->parseFromString($data);
			return TRUE;
		} catch (\Exception $ex){
			return FALSE;		
		}		
	}
}