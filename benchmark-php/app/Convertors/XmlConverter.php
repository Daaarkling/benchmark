<?php


namespace Benchmark\Converters;

use Nette\Utils\Json;
use SimpleXMLElement;

class XmlConverter implements IDataConverter
{


	public function convertData($arrayData)
	{
		$simpleXml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><result></result>");
		$this->arrayToXml($arrayData, $simpleXml);
		return $simpleXml->asXML();
	}


	/**
	 * @param array $array
	 * @param SimpleXMLElement $simpleXml
	 */
	private function arrayToXml($array, &$simpleXml ) {

		foreach($array as $key => $value ) {
			if( is_numeric($key) ){
				$key = 'item'.$key; //dealing with <0/>..<n/> issues
			}
			if( is_array($value) ) {
				$subnode = $simpleXml->addChild($key);
				self::arrayToXml($value, $subnode);
			} else {
				$simpleXml->addChild("$key",htmlspecialchars("$value"));
			}
		}
	}
}