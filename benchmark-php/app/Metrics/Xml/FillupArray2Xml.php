<?php

namespace Benchmark\Metrics\Xml;


use Benchmark\Metrics\AMetric;
use fillup\A2X;


class FillupArray2Xml extends AMetric
{

	public function serialize($data)
	{
		$a2x = new A2X($data);
		$xml = $a2x->asXml();
		return $xml;
	}

}