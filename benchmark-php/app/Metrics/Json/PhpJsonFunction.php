<?php

namespace Benchmark\Metrics\Json;


use Benchmark\Metrics\AMetric;


class PhpJsonFunction extends AMetric
{

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