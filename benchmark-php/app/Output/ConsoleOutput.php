<?php


namespace Benchmark;


use Benchmark\Output\AOutputHandler;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ConsoleOutput extends AOutputHandler
{
	/** @var InputInterface */
	protected $input;

	/** @var OutputInterface */
	protected $output;

	/** @var SymfonyStyle  */
	protected $io;
	
	
	public function __construct(InputInterface $input, OutputInterface $output)
	{
		$this->io = new SymfonyStyle($input, $output);
	}


	protected function printOutput($name, $headers, $rows)
	{
		$this->io->section($name);
		$this->io->table($headers, $rows);
	}

	public function printBenchmarkInfo($benchmarkInfo)
	{
		$this->io->section("Benchmark info");
		foreach ($benchmarkInfo as $key => $value) {
			$this->io->writeln($key . ": " . $value);
		}
	}


}