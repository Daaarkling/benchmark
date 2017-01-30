<?php


namespace Benchmark;


use Nette\Utils\FileSystem;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Style\SymfonyStyle;

class BenchmarkFileOutput extends Benchmark
{
	/** @var string */
	public static $fileName = 'php-benchmark-output.txt';

	/** @var BufferedOutput */
	protected $output;

	/** @var InputInterface */
	private $input;

	/** @var string */
	private $outputDir;


	public function __construct(Config $config, InputInterface $input, BufferedOutput $output, $outputDir)
	{
		parent::__construct($config);
		$this->outputDir = $outputDir;
		$this->input = $input;
		$this->output = $output;
	}

	protected function handleResult($headers, $rows)
	{
		$io = new SymfonyStyle($this->input, $this->output);
		$io->table($headers, $rows);

		$name = $this->outputDir . '/' . self::$fileName;
		FileSystem::write($name, $this->output->fetch());
	}


}