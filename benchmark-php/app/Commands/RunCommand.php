<?php

namespace Benchmark\Commands;


use Benchmark\Benchmark;
use Benchmark\Config;
use Benchmark\ConsoleOutput;
use Benchmark\CsvOutput;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RunCommand extends Command
{
	const OUTPUT_CSV = 'csv';
	const OUTPUT_CONSOLE = 'console';
	const OUTPUTS = [self::OUTPUT_CSV, self::OUTPUT_CONSOLE];



	protected function configure()
	{
		$this->setName('benchmark:run')
			->setDescription('Run benchmark.')
			->addOption('data', 't', InputOption::VALUE_REQUIRED, 'Set test data.')
			->addOption('repetitions', 'r', InputOption::VALUE_REQUIRED, 'Set number of repetitions', Config::REPETITIONS_DEFAULT)
			->addOption('output', 'o', InputOption::VALUE_REQUIRED, 'Set output. You can choose from several choices: ' . implode(', ', self::OUTPUTS) . '.', 'console')
			->addOption('format', 'f', InputOption::VALUE_REQUIRED, 'Run benchmark for specific format only.')
			->addOption('out-dir', 'd', InputOption::VALUE_REQUIRED, 'Output directory.');
	}



	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$io = new SymfonyStyle($input, $output);


		// test data
		$testData = $input->getOption('data');
		if ($testData !== NULL && ($realPath = realpath($testData))){
			$testData = $realPath;
		} elseif (($realPath = realpath(Config::$testDataFile))) {
			$testData = $realPath;
		} else {
			$io->error('Test data file was not found.');
			exit(1);
		}


		// repetitions
		$repetitions = $input->getOption('repetitions');
		if($repetitions !== NULL) {
			if($repetitions < 1) {
				$io->error("Repetitions must be whole number greater than zero.");
				exit(1);
			}
		} else {
			$repetitions = Config::REPETITIONS_DEFAULT;
		}


		// format
		$format = $input->getOption('format');
		if ($format !== NULL) {
			if (!in_array($format, Config::FORMATS)) {
				$io->error('Format must by one of these options: ' . implode(', ', Config::FORMATS));
				exit(1);
			}
		}


		// output
		$outputOption = $input->getOption('output');
		if ($outputOption !== NULL) {
			if (!in_array($outputOption, self::OUTPUTS)) {
				$io->error('Output must be one of these options: ' . implode(', ', self::OUTPUTS));
				return 1;
			}
		} else {
			$outputOption = self::OUTPUT_CONSOLE;
		}


		// output dir
		$outputDir = __DIR__ . '/../../output';
		if ($outputOption == self::OUTPUT_CSV && $input->getOption('out-dir') !== NULL) {
			$outputDir = $input->getOption('out-dir');
		}

		$config = new Config($testData, $repetitions, $format);


		switch ($outputOption) {
			case self::OUTPUT_CSV:
				$outputHandler = new CsvOutput($outputDir);
				break;
			default:
				$outputHandler = new ConsoleOutput($input, $output);
		}
		$benchmark = new Benchmark($config, $outputHandler);
		$benchmark->run();

		$io->title('Benchmark processed successfully!');
		return 0;
	}
}
