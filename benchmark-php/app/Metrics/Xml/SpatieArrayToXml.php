<?php

namespace Benchmark\Metrics\Xml;

use Benchmark\Config;
use Benchmark\Metrics\AMetric;
use Benchmark\Metrics\Info;
use Spatie\ArrayToXml\ArrayToXml;


class SpatieArrayToXml extends AMetric
{


	public function getInfo()
	{
		return new Info(Config::FORMAT_XML, 'spatie/array-to-xml', 'https://github.com/spatie/array-to-xml', '2.2.0');
	}

	public function serialize($data)
	{
		$xml = ArrayToXml::convert($data);
		return $xml;
	}

}