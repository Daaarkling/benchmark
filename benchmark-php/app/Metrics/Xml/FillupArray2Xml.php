<?php

namespace Benchmark\Metrics\Xml;


use Benchmark\Config;
use Benchmark\Metrics\AMetric;
use Benchmark\Metrics\Info;
use fillup\A2X;


class FillupArray2Xml extends AMetric
{
	public function getInfo()
	{
		return new Info(Config::FORMAT_XML, 'fillup/array2xml', 'https://github.com/fillup/array2xml', '0.5.1');
	}

	public function serialize($data)
	{
		$a2x = new A2X($data);
		$xml = $a2x->asXml();
		return $xml;
	}

}