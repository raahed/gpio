<?php
namespace GPIO\Interrupt;

use GPIO\Exception\InterruptException;

class Provider
{

    protected $watches = [];

    protected $timeout = 5000;

    public function register($pin, callable &$callable)
    {
        if (! is_callable($callable)) {}
    }

    protected function generateView()
    {}

    public function listen($timeout)
    {
        if ($timeout) {
            
            $this->setTimeout($timeout);
        }
    }

    public function setTimeout($timeout)
    {
        if (! is_int($timeout)) {
            
            throw new InterruptException("Timeout argument must be a int!");
        }
        
        $this->timeout = $timeout;
    }

    public function getTimeout()
    {
        return $this->timeout;
    }
}