<?php


namespace Benchmark\Utils;


class Formatters
{


	/**
	 * @link https://latte.nette.org/cs/filters#toc-bytes
	 *
	 * Converts to human readable file size and add units
	 * @param  int
	 * @param  int
	 * @return string
	 */
	public static function bytes($bytes, $precision = 2)
	{
		$bytes = round($bytes);
		$units = array('B', 'kB', 'MB', 'GB', 'TB', 'PB');
		foreach ($units as $unit) {
			if (abs($bytes) < 1024 || $unit === end($units)) {
				break;
			}
			$bytes = $bytes / 1024;
		}
		return round($bytes, $precision) . ' ' . $unit;
	}


	/**
	 * Converts seconds to human readable format and add units
	 *
	 * @param  float $seconds
	 * @param  int $precision
	 * @return string
	 */
	public static function seconds($seconds, $precision = 10)
	{
		$microseconds = $seconds * 1000 * 1000;
		$units = array('μs', 'ms', 's');
		foreach ($units as $unit) {
			if (abs($microseconds) < 1000 || $unit === end($units)) {
				break;
			}
			$microseconds = $microseconds / 1000;
		}
		return round($microseconds, $precision) . ' ' . $unit;
	}
}