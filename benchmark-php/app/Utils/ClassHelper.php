<?php

namespace Benchmark\Utils;


class ClassHelper
{
	/**
	 * Attempt to create object from given $className and check if implements $instanceof
	 *
	 * @param string $className
	 * @param string $instanceof
	 * @return object|NULL
	 */
	public static function instantiateClass($className, $instanceof)
	{
		if (!class_exists($className)) {
			return NULL;
		}
		$class = new $className();
		if (!$class instanceof $instanceof) {
			return NULL;
		}
		return $class;
	}
}