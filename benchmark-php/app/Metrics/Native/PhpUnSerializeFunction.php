<?php

namespace Benchmark\Metrics\Native;



use Benchmark\Metrics\AMetric;

class PhpUnSerializeFunction extends AMetric
{

	public function serialize($data)
	{
		return serialize($data);
	}

	public function deserialize($data)
	{
		$data = unserialize($data);
		return TRUE;
	}
}