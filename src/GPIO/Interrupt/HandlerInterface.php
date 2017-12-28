<?php
namespace GPIO\Interrupt\Handler;

interface HandlerInterface
{

    protected $port;

    public function interrupt($port, $value);
}