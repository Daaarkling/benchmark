<?php

namespace Benchmark\Metrics\Msgpack;


use Benchmark\Metrics\AMetric;

class MsgpackOfficial extends AMetric
{

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