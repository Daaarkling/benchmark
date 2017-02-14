<?php

namespace Benchmark\Metrics\Json;


use Benchmark\Config;
use Benchmark\Metrics\AMetric;
use Benchmark\Metrics\Info;
use Webmozart\Json\JsonDecoder;
use Webmozart\Json\JsonEncoder;


class WebmozartJson extends AMetric
{

	/** @var JsonDecoder */
	private $decoder;

	/** @var JsonEncoder */
	private $encoder;


	public function getInfo()
	{
		return new Info(Config::FORMAT_JSON, 'webmozart/json', 'https://github.com/webmozart/json', '1.2.2');
	}


	public function prepareBenchmark()
	{
		$this->decoder = new JsonDecoder();
		$this->encoder = new JsonEncoder();
	}


	public function serialize($data)
	{
		return $this->encoder->encode($data);
	}

	public function deserialize($data)
	{
		$data = $this->decoder->decode($data);
		return TRUE;
	}
}