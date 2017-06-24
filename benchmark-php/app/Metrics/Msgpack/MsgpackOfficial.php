<?php

namespace Benchmark\Metrics\Msgpack;


use Benchmark\Config;
use Benchmark\Metrics\AMetric;
use Benchmark\Metrics\Info;
use Benchmark\Metrics\MetricResult;

class MsgpackOfficial extends AMetric
{
    public function run($data, $dataFile, $inner = Config::REPETITIONS_INNER, $outer = Config::REPETITIONS_OUTER)
    {
        if (!extension_loaded('msgpack')) {
            return NULL;
        }
        return parent::run($data, $dataFile, $inner, $outer);
    }

    public function getInfo()
	{
		return new Info(Config::FORMAT_MSGPACK, 'msgpack/msgpack-php', 'https://github.com/msgpack/msgpack-php', '2.0.1');
	}

	public function serialize($data)
	{
		return msgpack_pack($data);
	}


	public function deserialize($data)
	{
		$data = msgpack_unpack($data);
		return TRUE;
	}
}