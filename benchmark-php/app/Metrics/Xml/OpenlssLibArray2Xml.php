<?php

namespace Benchmark\Metrics\Xml;


use Benchmark\Config;
use Benchmark\Metrics\AMetric;
use Benchmark\Metrics\Info;
use LSS\Array2XML;
use LSS\XML2Array;


class OpenlssLibArray2Xml extends AMetric
{

	public function getInfo()
	{
		return new Info(Config::FORMAT_XML, 'openlss/lib-array2xml', 'https://github.com/nullivex/lib-array2xml', '0.5.0');
	}

	public function serialize($data)
	{
		$xml = Array2XML::createXML('root', $data);
		return $xml->saveXML();
	}


	public function deserialize($data)
	{
		$data = XML2Array::createArray($data);
		return TRUE;
	}


}