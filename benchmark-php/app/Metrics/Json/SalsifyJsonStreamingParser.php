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

		// warm up
		$this->deserialize($dataFile);

		for ($j = 0; $j < IMetric::OUTER_REPETITION; $j++) {
			$time = 0;
			for ($i = 1; $i <= $repetitions; $i++) {
				$time += $this->deserialize($dataFile);
			}
			$result->addDeserialize($time);
		}
		return $result;
	}



	private function deserialize($data)
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