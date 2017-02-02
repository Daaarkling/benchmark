<?php


namespace Benchmark\Converters;

use Nette\Utils\Json;
use SimpleXMLElement;

class JsonConverter implements IDataConverter
{


	public function convertData($arrayData)
	{
		return json_encode($arrayData);
	}


}