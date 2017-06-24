<?php

namespace Benchmark\Metrics\Json;


use Benchmark\Config;
use Benchmark\Metrics\AMetric;
use Benchmark\Metrics\Info;


class BukkaPhpJsond extends AMetric
{
    public function run($data, $dataFile, $inner = Config::REPETITIONS_INNER, $outer = Config::REPETITIONS_OUTER)
    {
        if (!extension_loaded('jsond')) {
            return NULL;
        }
        return parent::run($data, $dataFile, $inner, $outer);
    }

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