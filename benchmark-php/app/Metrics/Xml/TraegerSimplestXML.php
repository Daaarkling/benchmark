<?php

namespace Benchmark\Metrics\Xml;

use Benchmark\Converters\XmlConverter;
use Benchmark\Metrics\AMetric;
use SimplestXML;


class TraegerSimplestXML extends AMetric
{

	/** @var SimplestXML */
	private $simplestXML;


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