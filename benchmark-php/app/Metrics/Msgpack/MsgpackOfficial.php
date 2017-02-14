<?php

namespace Benchmark\Metrics\Msgpack;


use Benchmark\Config;
use Benchmark\Metrics\AMetric;
use Benchmark\Metrics\Info;

class MsgpackOfficial extends AMetric
{
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