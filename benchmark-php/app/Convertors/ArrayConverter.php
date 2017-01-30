<?php


namespace Benchmark\Converters;


use Nette\Utils\Json;

class ArrayConverter implements IDataConverter
{


	public function convertData($jsonTestData)
	{
		return Json::decode($jsonTestData, Json::FORCE_ARRAY);
	}


}