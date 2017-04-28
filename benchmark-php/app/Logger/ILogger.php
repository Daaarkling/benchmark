<?php
namespace Becnhmark\Logger;


use Benchmark\Metrics\Info;

interface ILogger
{
    /**
     * @param Info $info
     */
    public function startMessage(Info $info);

    /**
     * @param Info $info
     */
    public function endMessage(Info $info);
}