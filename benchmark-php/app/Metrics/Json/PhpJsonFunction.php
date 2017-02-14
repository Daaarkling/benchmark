<?php

namespace Benchmark\Metrics\Json;


use Benchmark\Config;
use Benchmark\Metrics\AMetric;
use Benchmark\Metrics\Info;


class PhpJsonFunction extends AMetric
{
	public function getInfo()
	{
		return new Info(Config::FORMAT_JSON, 'PHP native json', 'http://php.net/manual/en/function.json-encode.php');
	}

	public function serialize($data)
	{
		return json_encode($data);
	}

	public function deserialize($data)
	{
		$data = json_decode($data);
		return TRUE;
	}
}