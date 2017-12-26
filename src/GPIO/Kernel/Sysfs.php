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
 * Extends the chip functions.
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
     * Contains the file stream object.
     *
     * @var object GPIO\File\Stream
     */
    protected static $stream = null;

    /**
     * Write the $port to
     * the export file.
     *
     * @param int $port
     *            The number of the gpio port.
     */
    static public function export($port)
    {
        if (! $port) {
            
            return;
        }
        
        $stream = self::streamhandler();
        
        $stream->open('export', Stream::FLAG_STREAM_WRITE);
        
        $stream->write($port, true);
    }

    /**
     * Write the $port to
     * the unexport file.
     *
     * @param int $port
     *            The number of the gpio port.
     */
    static public function unexport($port)
    {
        if (! $port) {
            
            return;
        }
        
        $stream = self::streamhandler();
        
        $stream->open('unexport', Stream::FLAG_STREAM_WRITE);
        
        $stream->write($port, true);
    }

    /**
     * Sets the direction of the
     * gpio $port.
     *
     * Allowed arguments -> in|out
     *
     * Leaves the $value argument open
     * to get the current direction.
     *
     * @param int $port
     *            The number of the gpio port.
     * @param string $value
     *            Sets the direction to in/out.
     * @throws KernelException
     * @return void|string Returns the direction if $value is empty.
     */
    static public function direction($port, $value)
    {
        if (! $port) {
            
            return;
        }
        
        if ($value && $value != 'in' && $value != 'out') {
            
            throw new KernelException("Unexpected direction! Try in/out.");
        }
        
        $stream = self::streamhandler();
        
        if ($value) {
            
            $stream->open('gpio' . $port . '/direction', Stream::FLAG_STREAM_WRITE);
            
            $stream->write($value, true);
        } else {
            
            $stream->open('gpio' . $port . '/direction', Stream::FLAG_STREAM_READ);
            
            return (string) $stream->read(true);
        }
    }

    /**
     * Write or reads the $value
     * from the $port.
     * Leave $value
     * open to read the value.
     *
     * @param int $port
     *            The number of the gpio port.
     * @param int $value
     *            Sets the value to 0/1.
     * @throws KernelException
     * @return void|int Leave the $value open to get a return.
     */
    static public function value($port, $value)
    {
        if (! $port) {
            
            return;
        }
        
        $stream = self::streamhandler();
        
        if ($value) {
            
            /**
             * Use intval..
             */
            $value = intval($value);
            
            if ($value != 0 && $value != 1) {
                
                throw new KernelException("Your value must be a boolean! (" . $value . ")");
            }
            
            $stream->open('gpio' . $port . '/value', Stream::FLAG_STREAM_WRITE);
            
            $stream->write($value, true);
        } else {
            
            $stream->open('gpio' . $port . '/value', Stream::FLAG_STREAM_READ);
            
            return (int) $stream->read(true);
        }
    }

    /**
     *
     * @param int $port
     *            The number of the gpio port.
     * @param int $value
     *            Sets the value to none/both/in/out default is none.
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
        
        $stream = self::streamhandler();
        
        if (! $value || $return == true) {
            
            $stream->open('gpio' . $port . '/edge', Stream::FLAG_STREAM_READ);
            
            $buffer = $stream->read($value);
            
            $stream->close();
        }
        
        if ($value) {
            
            $stream->open('gpio' . $port . '/edge', Stream::FLAG_STREAM_WRITE);
            
            $stream->write($value, true);
        }
        
        return (string) $buffer;
    }

    /**
     * Inverts the current $port signal.
     * Optional use the $return to get
     * the value after the invert.
     *
     * @param int $port
     *            The number of the gpio port.
     * @param int $value
     *            Sets the active_low (invert) value.
     * @param boolean $return
     *            In case the function returns the current port value.
     * @throws KernelException
     * @return void|void|int Use the $return to get a return.
     */
    static public function active_low($port, $value, $return = false)
    {
        if (! $port) {
            
            return;
        }
        
        $stream = self::streamhandler();
        
        $stream->open('gpio' . $port . '/active_low', Stream::FLAG_STREAM_WRITE);
        
        $stream->write($value, true);
        
        if ($return) {
            
            return self::value($port);
        }
    }

    /**
     * Returns the file stream object.
     *
     * @return \GPIO\File\Stream
     */
    static protected function streamhandler()
    {
        if (! self::$stream) {
            
            self::$stream = new Stream();
        }
        
        return self::$stream;
    }
}