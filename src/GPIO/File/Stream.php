<?php
namespace GPIO\File;

use GPIO\Exception\FileException;

class Stream
{

    const FLAG_STREAM_WRITE = 1;

    const FLAG_STREAM_READ = 2;

    const FLAG_STREAM_PERM = 4;

    const FLAG_BASE_VALUE = 0;

    protected $base = '/sys/class/gpio';

    protected $mode = '';

    protected $stream = null;

    public function __construct($context, $flags)
    {
        if ($context) {
            
            $this->open($context, $flags);
        }
    }

    public function __destruct()
    {
        if ($this->stream) {
            
            $this->close();
        }
    }

    public function __toString()
    {
        if ($this->stream) {
            
            return $this->read(true);
        }
    }

    public function open($context, $flags = self::FLAG_BASE_VALUE)
    {
        if (! $context) {
            
            throw new FileException("Missing context in file stream.");
        }
        
        if ($flags & self::FLAG_STREAM_READ) {
            
            $this->mode = 'r';
        } elseif ($flags & self::FLAG_STREAM_WRITE) {
            
            $this->mode = 'w';
        } else {
            
            $this->mode = 'w+';
        }
        
        if (strpos($context, '/') != 0) {
            
            $file = $this->base . '/' . $context;
        } else {
            
            $file = $this->base . $context;
        }
        
        try {
            
            /**
             * include base = flase
             */
            $this->stream = fopen($file, $this->mode, false);
        } catch (\Exception $e) {
            
            throw new FileException("Open stream context failed: " . $e->getMessage());
        }
    }

    public function close()
    {
        if ($this->stream) {
            
            fclose($this->stream);
            
            if ($this->stream) {
                
                $this->stream = null;
            }
            
            $this->mode = '';
        }
    }

    public function read($close = false)
    {
        if ($this->stream) {
            
            $buffer = fread($this->stream);
            
            if ($close == true) {
                
                $this->close;
            }
            
            return $buffer;
        }
    }

    public function write($content = '', $close = false)
    {
        if ($this->stream) {
            
            if ($this->mode == 'r') {
                
                throw new \GPIO\Exception\FileException("Try to write something to a read-only stream.");
            }
            
            fwrite($this->stream, $content);
            
            if ($close == true) {
                
                $this->close();
            }
        }
    }

    public function setBase($base)
    {
        if (! $base) {
            
            return;
        }
        
        if (substr($base, - 1) == '/') {
            
            $base = substr($base, 0, - 1);
        }
        
        if (! is_dir($base)) {
            
            throw new FileException("Cant find new base: " . $base);
        }
        
        $this->base = $base;
    }

    public function getBase()
    {
        return $this->base;
    }

    public function getMode()
    {
        return $this->mode;
    }
}