<?php

namespace Benchmark\Metrics\Json;


use Benchmark\Config;
use Benchmark\Metrics\AMetric;
use Benchmark\Metrics\Info;


class BukkaPhpJsond extends AMetric
{
	public function getInfo()
	{
		return new Info(Config::FORMAT_JSON, 'bukka/php-jsond', 'https://github.com/bukka/php-jsond', '1.3.0');
	}

	public function serialize($data)
	{
		return jsond_encode($data);
	}

	public function deserialize($data)
	{
		$data = jsond_decode($data);
		return TRUE;
	}
}