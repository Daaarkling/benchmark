<?php

namespace Benchmark\Metrics\Protobuf;


use Benchmark\Config;
use Benchmark\Converters\AllegroPhpProtobuf\PersonCollection;
use Benchmark\Converters\AllegroPhpProtobuf\ProtobufConverter;
use Benchmark\Metrics\AMetric;
use Benchmark\Metrics\Info;


class AllegroPhpProtobuf //extends AMetric
{
	/** @var  PersonCollection */
	private $personCollection;

    public function run($data, $dataFile, $inner = Config::REPETITIONS_INNER, $outer = Config::REPETITIONS_OUTER)
    {
        if (!extension_loaded('protobuf')) {
            return NULL;
        }
    //    return parent::run($data, $dataFile, $inner, $outer);
    }

	public function getInfo()
	{
		return new Info(Config::FORMAT_PROTOBUF, 'serggp/php-protobuf', 'https://github.com/serggp/php-protobuf', '0.10');
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
		try {
			$data = $this->personCollection->parseFromString($data);
			return TRUE;
		} catch (\Exception $ex){
			return FALSE;		
		}		
	}
}
