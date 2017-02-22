<?php

use Benchmark\Commands\RunCommand;
use Composer\Autoload\ClassLoader;
use Nette\Caching\Storages\FileStorage;
use Nette\Loaders\RobotLoader;
use Symfony\Component\Console\Application;


/** @var ClassLoader $composer */
$composer = require __DIR__ . '/vendor/autoload.php';

$loader = new RobotLoader;
$loader->addDirectory(__DIR__ . '/app')
	->addDirectory(__DIR__ . '/libs')
	->setCacheStorage(new FileStorage(__DIR__ . '/temp'));
//$loader->setTempDirectory('temp');
$loader->register();

$app = new Application();
$app->add(new RunCommand());
$app->run();