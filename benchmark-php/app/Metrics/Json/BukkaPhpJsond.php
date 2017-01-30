<?php

namespace Benchmark\Metrics\Json;


use Benchmark\Metrics\AMetric;


class BukkaPhpJsond extends AMetric
{

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