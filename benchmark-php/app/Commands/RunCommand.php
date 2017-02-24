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
			->addOption('outer', 'o', InputOption::VALUE_REQUIRED, 'Number of outer repetitions')
			->addOption('inner', 'i', InputOption::VALUE_REQUIRED, 'Number of inner repetitions')
			->addOption('result', 'r', InputOption::VALUE_REQUIRED, 'Set output. You can choose from several choices: ' . implode(', ', self::OUTPUTS) . '.', 'console')
			->addOption('format', 'f', InputOption::VALUE_OPTIONAL, 'Run benchmark for specific format only.')
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


		// inner repetitions
		$inner = $input->getOption('inner');
		if($inner !== NULL) {
			if($inner < 1) {
				$io->error("Number of inner repetitions must be whole number greater than zero.");
				exit(1);
			}
		} else {
			$inner = Config::REPETITIONS_INNER;
		}

		// outer repetitions
		$outer = $input->getOption('outer');
		if($outer !== NULL) {
			if($outer < 1) {
				$io->error("Number of outer repetitions must be whole number greater than zero.");
				exit(1);
			}
		} else {
			$outer = Config::REPETITIONS_OUTER;
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
		$outputOption = $input->getOption('result');
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

		$config = new Config($testData, $inner, $outer, $format);


		switch ($outputOption) {
			case self::OUTPUT_CSV:
				$outputHandler = new CsvOutput($outer, $outputDir);
				break;
			default:
				$outputHandler = new ConsoleOutput($outer, $input, $output);
		}
		$benchmark = new Benchmark($config, $outputHandler);
		$benchmark->run();

		$io->title('Benchmark processed successfully!');
		return 0;
	}
}
