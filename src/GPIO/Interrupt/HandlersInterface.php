<?php
namespace GPIO\Interrupt\Handler;

interface HandlersInterface
{

    protected $port;

    public function interrupt($port, $value);
}