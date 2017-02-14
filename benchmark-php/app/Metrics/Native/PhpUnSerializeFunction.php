<?php

namespace Benchmark\Metrics\Native;



use Benchmark\Config;
use Benchmark\Metrics\AMetric;
use Benchmark\Metrics\Info;

class PhpUnSerializeFunction extends AMetric
{

	public function getInfo()
	{
		return new Info(Config::FORMAT_NATIVE, 'PHP serialize', 'http://php.net/manual/en/function.serialize.php');
	}


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