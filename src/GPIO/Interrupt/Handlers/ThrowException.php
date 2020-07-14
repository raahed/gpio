<?php
namespace GPIO\Interrupt\Handler;

use GPIO\Exception\IOException;

class ThrowException implements HandlersInterface
{

    protected static $port;

    public function interrupt($port, $value)
    {
        $this->port = $port;
        
        throw new IOException("The Pin " . $this->port . " was changed to " . $value);
    }
}