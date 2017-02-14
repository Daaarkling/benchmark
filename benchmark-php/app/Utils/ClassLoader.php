<?php

namespace Benchmark\Utils;


use Benchmark\Metrics\IMetric;
use Nette\Loaders\RobotLoader;

class ClassLoader
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


	/**
	 * @param string $interfaceName
	 * @return object[]
	 */
	public static function loadClasses($interfaceName = IMetric::class) {

		$loader = new RobotLoader();
		$loader->addDirectory(__DIR__ . '/../Metrics')
			->setTempDirectory(__DIR__ . '/../../temp')
			->register();


		$classes = array_filter(array_keys($loader->getIndexedClasses()), function($className) use ($interfaceName) {
				$reflectionClass = new \ReflectionClass($className);
				return $reflectionClass->implementsInterface($interfaceName) && $reflectionClass->isInstantiable();
			}
		);

		$objects = [];
		foreach ($classes as $class) {
			$objects[] = self::instantiateClass($class, $interfaceName);
		}

		return $objects;
	}
}