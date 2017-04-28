<?php

namespace Benchmark\Commands;


use Becnhmark\Logger\ILogger;
use Benchmark\Metrics\Info;
use Nette\Utils\DateTime;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ConsoleLogger implements ILogger
{
    /** @var SymfonyStyle */
    private $io;


    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    public function startMessage(Info $info)
    {
		$timestamp = $this->getCurrentTimestamp();
        $this->io->text('[INFO] ' . $timestamp . ' Benchmark of "' . $info->getFullName() . '" has started.');
    }

    public function endMessage(Info $info)
    {
    	$timestamp = $this->getCurrentTimestamp();
        $this->io->text('[INFO] ' . $timestamp . ' Benchmark of "' . $info->getFullName() . '" has finished.');
    }

	/**
	 * @return string
	 */
    private function getCurrentTimestamp() {

    	return (new DateTime('now'))->format('Y-m-d H:i:s');
	}
}