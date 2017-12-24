<?php
/**
 * GPIO\Kernel namespace
 */
namespace GPIO\Kernel;

use GPIO\Exception\KernelException;
use GPIO\File\Stream;
use GPIO\Interrupt\Provider as InterruptProvider;

/**
 * Contains the main gpio functions,
 * in a static and simple way.
 * Entends the chip functions.
 * 
 * Uses mainly the File Stream
 * class.
 * 
 * @author raah
 * @link https://www.kernel.org/doc/Documentation/gpio/sysfs.txt
 */
class Sysfs extends Chip
{

    /**
     * Write the $port to
     * the export file.
     * 
     * @param int $port
     */
    static public function export($port)
    {
        if (! $port) {
            
            return;
        }
        
        $stream = new Stream('export', Stream::FLAG_STREAM_WRITE);
        
        $stream->write($port, true);
    }

    /**
     * Write the $port to
     * the unexport file.
     * 
     * @param int $port
     */
    static public function unexport($port)
    {
        if (! $port) {
            
            return;
        }
        
        $stream = new Stream('unexport', Stream::FLAG_STREAM_WRITE);
        
        $stream->write($port, true);
    }

    /**
     * Sets the direction of the
     * gpio $port.
     * Allowed arguments -> in|out
     * 
     * @param int $port
     * @param string $value
     * @throws KernelException
     * @return void|string
     */
    static public function direction($port, $value)
    {
        if (! $port) {
            
            return;
        }
        
        if($value && $value != 'in' && $value != 'out') {
            
            throw new KernelException("Unexpected direction! Try in/out.");
            
        }
        
        if ($value) {
            
            $stream = new Stream('gpio' . $port . '/direction', Stream::FLAG_STREAM_WRITE);
            
            $stream->write($value, true);
        } else {
            
            $stream = new Stream('gpio' . $port . '/direction', Stream::FLAG_STREAM_READ);
            
            return $stream->read(true);
        }
    }

    /**
     * Write or reads the $value
     * from the $port. Leave $value
     * open to read the value.
     * 
     * @param int $port
     * @param int $value
     * @throws KernelException
     * @return void|string
     */
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

    /**
     * 
     * @param int $port
     * @param int $value
     * @param InterruptProvider $interrupt
     * @param boolean $return
     * @throws KernelException
     * @return void|string
     */
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

    /**
     * Inverts the current $port signal.
     * Optional use the $return to get
     * the value after the invert.
     * 
     * @param int $port
     * @param boolean $return
     * @throws KernelException
     * @return void|void|string
     */
    static public function active_low($port, $return = false)
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
        
        if($return) {
            
            return self::value($port);
            
        }
        
    }
}