<?php
namespace GPIO\Interrupt\Handler;

class EchoChanges implements HandlersInterface
{

    protected $port;

    public function interrupt($port, $value)
    {
        $this->port = $port;
        
        echo "The Pin " . $this->port . " was changed to " . $value;
    }
}