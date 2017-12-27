<?php
/**
 * GPIO\Kernel namespace
 */
namespace GPIO\Kernel;

use GPIO\File\Stream;
use GPIO\Exception\KernelException;

/**
 * Contains the main gpio functions,
 * in a static and simple way.
 *
 * @author raah
 * @see https://www.kernel.org/doc/Documentation/gpio/sysfs.txt
 */
class Chip
{

    /**
     * Contains the file stream object.
     *
     * @var object GPIO\File\Stream
     */
    protected static $stream = null;

    /**
     * Stores the chip.
     * Set them with setChip().
     *
     * @var string $chip
     */
    protected static $chip = '';

    /**
     * Reads and returns the content
     * from the base file.
     * Optionaly
     * argument, set the chip.
     *
     * @param string $chip
     *            Optional: Leave this option open if the chip is already defined.
     * @throws KernelException
     * @return int Returns base parameter.
     */
    static public function base($chip)
    {
        if ($chip) {
            
            self::setChip($chip);
        }
        
        if (self::$chip == '') {
            
            throw new KernelException("No chip locate! Use setChip()");
        }
        
        $stream = self::streamhandler();
        
        $stream->open(self::$chip . '/base', Stream::FLAG_STREAM_READ);
        
        return (int) $stream->read(true);
    }

    /**
     * Reads and returns the content
     * from the label file.
     * Optionaly
     * argument, set the chip.
     *
     * @param string $chip
     *            Optional: Leave this option open if the chip is already defined.
     * @throws KernelException
     * @return string Returns the label parameter.
     */
    static public function label($chip)
    {
        if ($chip) {
            
            self::setChip($chip);
        }
        
        if (self::$chip == '') {
            
            throw new KernelException("No chip locate! Use setChip()");
        }
        
        $stream = self::streamhandler();
        
        $stream->open(self::$chip . '/label', Stream::FLAG_STREAM_READ);
        
        return $stream->read(true);
    }

    /**
     * Reads and returns the content
     * from the ngpio file.
     * Optionaly
     * argument, set the chip.
     *
     * @param string $chip
     *            Optional: Leave this option open if the chip is already defined.
     * @throws KernelException
     * @return int Returns the ngpio parameter.
     */
    static public function ngpio($chip)
    {
        if ($chip) {
            
            self::setChip($chip);
        }
        
        if (self::$chip == '') {
            
            throw new KernelException("No chip locate! Use setChip()");
        }
        
        $stream = self::streamhandler();
        
        $stream->open(self::$chip . '/ngpio', Stream::FLAG_STREAM_READ);
        
        return (int) $stream->read(true);
    }

    /**
     * Returns the $chip value
     *
     * @return string $chip
     */
    static public function getChip()
    {
        return self::$chip;
    }

    /**
     * Sets the $chip value.
     *
     * @param string $chip
     *            Set the chip like "gpiochip0".
     * @throws KernelException
     */
    static public function setChip($chip)
    {
        if (! $chip) {
            
            return;
        }
        
        /**
         * Single line stream comment.
         *
         * @var Ambiguous $base
         */
        $base = self::streamhandler()->getBase();
        
        if (is_dir($base . '/' . $chip)) {
            
            self::$chip = $chip;
        } else {
            
            throw new KernelException("Cant find Chip by: " . $base . '/' . $chip);
        }
    }

    /**
     * Unsets the $chip value.
     */
    static public function unsetChip()
    {
        self::$chip = '';
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