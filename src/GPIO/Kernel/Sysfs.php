<?php
namespace GPIO\Kernel;

use GPIO\Exception\KernelException;
use GPIO\File\Stream;
use GPIO\Interrupt\Provider as InterruptProvider;

class Sysfs extends Chip
{

    static public function export($port)
    {
        if (! $port) {
            
            return;
        }
        
        $stream = new Stream('export', Stream::FLAG_STREAM_WRITE);
        
        $stream->write($port, true);
    }

    static public function unexport($port)
    {
        if (! $port) {
            
            return;
        }
        
        $stream = new Stream('unexport', Stream::FLAG_STREAM_WRITE);
        
        $stream->write($port, true);
    }

    static public function direction($port, $value)
    {
        if (! $port) {
            
            return;
        }
        
        $stream = new Stream('gpio' . $port . '/direction', Stream::FLAG_STREAM_WRITE);
        
        $stream->write($value, true);
    }

    static public function value($port, $value)
    {
        if (! $port) {
            
            return;
        }
        
        if ($value) {
            
            /**
             * Use intval..
             */
            $value = intval($value);
            
            if ($value != 0 && $value != 1) {
                
                throw new KernelException("Your value must be a boolean! (" . $value . ")");
            }
            
            $stream = new Stream('gpio' . $port . '/value', Stream::FLAG_STREAM_WRITE);
            
            $stream->write($value, true);
        } else {
            
            $stream = new Stream('gpio' . $port . '/value', Stream::FLAG_STREAM_READ);
            
            return $stream->read(true);
        }
    }

    static public function edge($port, $value, InterruptProvider $interrupt = InterruptProvider, $return = false)
    {
        
        // TODO: Add Interrupt
        $buffer;
        
        if (! $port) {
            
            return;
        }
        
        if ($value && $value != 'none' && $value != 'both' && $value != 'in' && $value != 'out') {
            
            throw new KernelException("Unexpected value type: " . $value);
        }
        
        $stream = new Stream();
        
        if (! $value || $return == true) {
            
            $stream->open('gpio' . $port . '/edge', Stream::FLAG_STREAM_READ);
            
            $buffer = $stream->read($value);
            
            $stream->close();
        }
        
        if ($value) {
            
            $stream->open('gpio' . $port . '/edge', Stream::FLAG_STREAM_WRITE);
            
            $stream->write($value, true);
        }
        
        return $buffer;
    }

    static public function active_low($port)
    {
        if (! $port) {
            
            return;
        }
        
        $stream = new Stream('gpio' . $port . '/value', Stream::FLAG_STREAM_READ);
        
        /**
         * Use intval..
         *
         * @var Ambiguous $buffer
         */
        $buffer = intval($stream->read(true));
        
        if ($buffer == 1 || $buffer == 0) {
            
            $value = $buffer == 1 ? 0 : 1;
        } else {
            
            throw new KernelException("The gpio returns a non boolean value: " . $buffer);
        }
        
        $stream->open('gpio' . $port . '/value', Stream::FLAG_STREAM_WRITE);
        
        $stream->write($value, true);
    }
}