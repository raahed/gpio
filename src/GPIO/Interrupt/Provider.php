<?php
namespace GPIO\Interrupt;

use GPIO\Kernel\Sysfs;
use GPIO\Exception\InterruptException;
use GPIO\File\Stream;

class Provider
{

    protected $watchlist = [];

    protected $timeout = 5000;

    public function __construct()
    {}

    public function __destruct()
    {
        foreach ($this->watchlist as $port) {
            
            $this->unregister($port);
        }
    }

    public function register($port, callable $callable, $edge)
    {
        if (! is_callable($callable)) {
            
            throw new InterruptException("Cant use callable for interrupt, port " . $port);
        }
        
        if ($this->watchlist[$port]) {
            
            return;
        }
        
        if ($edge) {
            
            Sysfs::edge($port, $edge);
        }
        
        if (Sysfs::edge($port) == 'none') {
            
            throw new InterruptException("The port " . $port . " has no edge.");
        }
        
        $this->watchlist[$port] = [
            'callable' => $callable,
            'port' => $port,
            'stream' => new Stream() // Empty stream
        
        ];
    }

    public function unregister($port)
    {
        if ($this->watchlist[$port]) {
            
            $this->watchlist[$port] = null;
        }
    }

    public function listen($timeout)
    {
    
    /**
     */
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

    public function getWatchlistEnteties()
    {}
}