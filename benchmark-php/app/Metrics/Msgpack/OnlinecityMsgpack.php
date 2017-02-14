<?php

namespace Benchmark\Metrics\Msgpack;


use Benchmark\Config;
use Benchmark\Metrics\AMetric;
use Benchmark\Metrics\Info;

require_once __DIR__ . '/../../../libs/onlinecity-msgpack-php/msgpack.php';

class OnlinecityMsgpack extends AMetric
{
	public function getInfo()
	{
		return new Info(Config::FORMAT_MSGPACK, 'onlinecity/msgpack-php', 'https://github.com/onlinecity/msgpack-php');
	}

	public function serialize($data)
	{
		return msgpack_pack_o($data);
	}

    public function deserialize($data)
    {
		$data = msgpack_unpack_o($data);
		return TRUE;
    }
}
