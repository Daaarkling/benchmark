<?php


namespace Benchmark\Converters\GoogleProtobuf;



use Benchmark\Converters\IDataConverter;

class ProtobufBinaryConverter extends ProtobufConverter implements IDataConverter
{


	public function convertData($jsonTestData)
	{
		$persons = parent::convertData($jsonTestData);
		return $persons->encode();
	}


}