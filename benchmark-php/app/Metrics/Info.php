<?php
/**
 * Created by PhpStorm.
 * User: Jan
 * Date: 14. 2. 2017
 * Time: 13:18
 */

namespace Benchmark\Metrics;


use Benchmark\Config;

class Info
{
	/** @var string */
	private $format;

	/** @var string */
	private $name;

	/** @var string */
	private $url;

	/** @var string */
	private $version;

	/**
	 * Info constructor.
	 * @param string $format
	 * @param string $name
	 * @param string $url
	 * @param string $version
	 */
	public function __construct($format, $name, $url, $version = NULL)
	{
		$this->format = $format;
		$this->name = $name;
		$this->url = $url;
		$this->version = $version;
	}

	/**
	 * @return string
	 */
	public function getFullName()
	{
		if (!$this->version){
			return 'php - ' . $this->format . ' - ' . $this->name;
		}
		return 'php - ' . $this->format . ' - ' . $this->name . ' ' . $this->version;
	}

	/**
	 * @return string
	 */
	public function getFormat()
	{
		return $this->format;
	}

	/**
	 * @param string $format
	 */
	public function setFormat($format)
	{
		if (in_array($format, Config::FORMATS)) {
			$this->format = $format;
		}
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * @param string $url
	 */
	public function setUrl($url)
	{
		$this->url = $url;
	}

	/**
	 * @return string
	 */
	public function getVersion()
	{
		return $this->version;
	}

	/**
	 * @param string $version
	 */
	public function setVersion($version)
	{
		$this->version = $version;
	}


}