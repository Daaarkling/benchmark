<?php

namespace Benchmark\Metrics\Json;


use Benchmark\Config;
use Benchmark\Metrics\IMetric;
use Benchmark\Metrics\MetricResult;
use JsonStreamingParser\Listener\InMemoryListener;
use JsonStreamingParser\Parser;


class SalsifyJsonStreamingParser implements IMetric
{


	public function run($data, $dataFile, $repetitions = Config::REPETITIONS_DEFAULT)
	{
		$result = new MetricResult();

		$time = 0;
		for ($i = 1; $i <= $repetitions; $i++) {
			$time += $this->deserialize($dataFile);
		}

		$result->setDeserialize($time);
		return $result;
	}

	public function serialize($data)
	{
	}



	public function deserialize($data)
	{
		$listener = new InMemoryListener();
		$stream = fopen($data, 'r');
		try {
			$parser = new Parser($stream, $listener);
			$start = microtime(TRUE);
			$parser->parse();
			$time = microtime(TRUE) - $start;
			fclose($stream);
			return $time;
		} catch (\Exception $e) {
			fclose($stream);
			return FALSE;
			//throw $e;
		}
	}
}