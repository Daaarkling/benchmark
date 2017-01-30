<?php


namespace Benchmark\Converters;


interface IDataConverter
{

	/**
	 * Take test data and convert them into format which satisfy concrete type of encoder/decoder such as xml, json,...
	 *
	 * @param string $jsonTestData - json file with test data
	 * @return mixed - converted data
	 */
	public function convertData($jsonTestData);
}