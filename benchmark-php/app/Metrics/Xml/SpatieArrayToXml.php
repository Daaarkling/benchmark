<?php

namespace Benchmark\Metrics\Xml;

use Benchmark\Metrics\AMetric;
use Spatie\ArrayToXml\ArrayToXml;


class SpatieArrayToXml extends AMetric
{

	public function serialize($data)
	{
		$xml = ArrayToXml::convert($data);
		return $xml;
	}

}