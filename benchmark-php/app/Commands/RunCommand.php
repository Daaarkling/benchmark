<?php

namespace Benchmark\Commands;


use Benchmark\BenchmarkConsoleOutput;
use Benchmark\BenchmarkCsvOutput;
use Benchmark\BenchmarkDumpOutput;
use Benchmark\BenchmarkFileOutput;
use Benchmark\Config;
use Benchmark\Utils\ConfigValidator;
use Nette\Utils\Json;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RunCommand extends Command
{
	const OUTPUT_FILE = 'file';
	const OUTPUT_CSV = 'csv';
	const OUTPUT_CONSOLE = 'console';
	const OUTPUTS = [self::OUTPUT_FILE, self::OUTPUT_CSV, self::OUTPUT_CONSOLE];






	protected function configure()
	{
		$this->setName('benchmark:run')
			->setDescription('Run benchmark with given config and test data.')
			->addOption('config', 'c', InputOption::VALUE_REQUIRED, 'Set config file.')
			->addOption('data', 't', InputOption::VALUE_REQUIRED, 'Set test data.')
			->addOption('repetitions', 'r', InputOption::VALUE_REQUIRED, 'Set number of repetitions', Config::REPETITIONS_DEFAULT)
			->addOption('output', 'o', InputOption::VALUE_REQUIRED, 'Set output. You can choose from several choices: ' . implode(', ', self::OUTPUTS) . '.', 'console')
			->addOption('format', 'f', InputOption::VALUE_REQUIRED, 'Run benchmark for specific format only.')
			->addOption('out_dir', 'd', InputOption::VALUE_REQUIRED, 'Output directory.');
	}



	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$io = new SymfonyStyle($input, $output);

		// config
		$configFile = $input->getOption('config');
		if ($configFile !== NULL && ($configReal = realpath($configFile))) {
			$configFile = $configReal;
		} elseif (($configReal = realpath(Config::$configFile))) {
			$configFile = $configReal;
		}
		$configNode = Json::decode(file_get_contents($configFile));


		// test data
		$testData = $input->getOption('data');
		if ($testData !== NULL && ($realPath = realpath($testData))){
			$testData = $realPath;
		} elseif (($realPath = realpath(Config::$testDataFile))) {
			$testData = $realPath;
		}


		// repetitions
		$repetitions = $input->getOption('repetitions');


		// format
		$format = $input->getOption('format');


		// output
		$outputOption = $input->getOption('output');
		if (!in_array($outputOption, self::OUTPUTS)) {
			$io->error('Output must be one of these options: ' . implode(', ', self::OUTPUTS));
			return 1;
		}

		// output dir
		$outputDir = __DIR__ . '/../../output';
		if (($outputOption == self::OUTPUT_CSV || $outputOption == self::OUTPUT_FILE) && $input->getOption('out_dir') !== NULL) {
			$outputDir = $input->getOption('out_dir');
		}

		$config = new Config($configNode, $testData, $repetitions, $format);

		// validation
		$validator = new ConfigValidator($config);
		$validator->validate();

		// validation is OK
		if ($validator->isValid()) {

			$io->title('Validation succeeded!');

			if ($outputOption === self::OUTPUT_FILE) {
				$bufferedOutput = new BufferedOutput();
				$benchmark = new BenchmarkFileOutput($config, $input, $bufferedOutput, $outputDir);
			}
			elseif ($outputOption === self::OUTPUT_CSV) {
				$benchmark = new BenchmarkCsvOutput($config, $outputDir);
			}
			else {
				$benchmark = new BenchmarkConsoleOutput($config, $input, $output);
			}

			$benchmark->run();

			$io->title('Benchmark processed successfully!');
			return 0;

		} else {

			// errors
			foreach ($validator->getErrors() as $error) {
				$io->error(sprintf("'%s' %s", $error['property'], $error['message']));
			}
			return 1;
		}
	}
}