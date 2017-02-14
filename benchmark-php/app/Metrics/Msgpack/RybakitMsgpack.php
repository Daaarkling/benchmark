<?php

namespace Benchmark\Metrics\Msgpack;


use Benchmark\Config;
use Benchmark\Metrics\AMetric;
use Benchmark\Metrics\Info;
use MessagePack\BufferUnpacker;
use MessagePack\Packer;


class RybakitMsgpack extends AMetric
{
	/** @var BufferUnpacker */
    private $bufferUnpacker;

	/** @var Packer */
	private $packer;


	public function getInfo()
	{
		return new Info(Config::FORMAT_MSGPACK, 'rybakit/msgpack', 'https://github.com/rybakit/msgpack.php', '0.2.1');
	}


	public function prepareBenchmark()
	{
		$this->bufferUnpacker = new BufferUnpacker();
		$this->packer = new Packer(Packer::FORCE_STR | Packer::FORCE_MAP);
	}


	public function serialize($data)
	{
		return $this->packer->pack($data);
	}


    public function deserialize($data)
    {
        $data = $this->bufferUnpacker->reset($data)->unpack();
		return TRUE;
    }
}
