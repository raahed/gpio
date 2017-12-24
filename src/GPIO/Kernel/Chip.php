<?php
/**
 * GPIO\Kernel namespace
 */
namespace GPIO\Kernel;

use GPIO\File\Stream;
use GPIO\Exception\FileException;
use GPIO\Exception\KernelException;

/**
 * Contains the main gpio functions,
 * in a static and simple way.
 *
 * Extended by the Sysfs!
 *
 * @author raah
 * @link https://www.kernel.org/doc/Documentation/gpio/sysfs.txt
 */
class Chip
{

    /**
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
     * @throws KernelException
     * @return string
     */
    static public function base($chip)
    {
        if ($chip) {
            
            self::setChip($chip);
        }
        
        if (self::$chip == '') {
            
            throw new KernelException("No chip locate! Use setChip()");
        }
        
        $stream = new Stream(self::$chip . '/base', Stream::FLAG_STREAM_READ);
        
        return $stream->read(true);
    }

    /**
     * Reads and returns the content
     * from the label file.
     * Optionaly
     * argument, set the chip.
     *
     * @param string $chip
     * @throws KernelException
     * @return string
     */
    static public function label($chip)
    {
        if ($chip) {
            
            self::setChip($chip);
        }
        
        if (self::$chip == '') {
            
            throw new KernelException("No chip locate! Use setChip()");
        }
        
        $stream = new Stream(self::$chip . '/label', Stream::FLAG_STREAM_READ);
        
        return $stream->read(true);
    }

    /**
     * Reads and returns the content
     * from the ngpio file.
     * Optionaly
     * argument, set the chip.
     *
     * @param string $chip
     * @throws KernelException
     * @return string
     */
    static public function ngpio($chip)
    {
        if ($chip) {
            
            self::setChip($chip);
        }
        
        if (self::$chip == '') {
            
            throw new KernelException("No chip locate! Use setChip()");
        }
        
        $stream = new Stream(self::$chip . '/ngpio', Stream::FLAG_STREAM_READ);
        
        return $stream->read(true);
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
     * @throws FileException
     */
    static public function setChip($chip)
    {
        if (! $chip) {
            
            return;
        }
        
        $stream = new Stream();
        
        $base = $stream->getBase();
        
        $stream->close();
        
        if (is_dir($base . '/' . $chip)) {
            
            self::$chip = $chip;
        } else {
            
            throw new FileException("Cant find Chip by: " . $base . '/' . $chip);
        }
    }

    /**
     * Unsets the $chip value.
     */
    static public function unsetChip()
    {
        self::$chip = '';
    }
}