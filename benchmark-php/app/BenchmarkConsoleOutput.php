<?php


namespace Benchmark;


use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class BenchmarkConsoleOutput extends Benchmark
{
	/** @var InputInterface */
	protected $input;

	/** @var OutputInterface */
	protected $output;

	
	
	public function __construct(Config $config, InputInterface $input, OutputInterface $output)
	{
		parent::__construct($config);
		
		$this->input = $input;
		$this->output = $output;
	}


	protected function handleResult($headers, $rows)
	{
		$io = new SymfonyStyle($this->input, $this->output);
		$io->table($headers, $rows);
	}
}