<?php

namespace Benchmark\Metrics\Xml;

use Benchmark\Config;
use Benchmark\Converters\XmlConverter;
use Benchmark\Metrics\AMetric;
use Benchmark\Metrics\Info;
use SimplestXML;


class TraegerSimplestXML extends AMetric
{

	/** @var SimplestXML */
	private $simplestXML;


	public function getInfo()
	{
		return new Info(Config::FORMAT_XML, 'traeger/SimplestXML', 'https://github.com/traeger/SimplestXML', 'r1');
	}


	public function prepareBenchmark()
	{
		$this->simplestXML = new SimplestXML();
	}

	public function prepareDataForDeserialize()
	{
		$xmlConverter = new XmlConverter();
		return $xmlConverter->convertData($this->data);
	}

	public function deserialize($data)
	{
		$data = $this->simplestXML->from_xml($data);
		return TRUE;
	}


}