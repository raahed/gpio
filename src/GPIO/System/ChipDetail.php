<?php
/**
 * GPIO\System namespace
 */
namespace GPIO\System;

/**
 * Namespace imports
 */
use GPIO\Kernel\Chip;
use GPIO\Exception\SystemException;
use GPIO\File\Stream;

/**
 * Chip helper class.
 * Some useful functions.
 *
 * @author raah
 */
class ChipDetail
{

    /**
     * Returns all chip labels.
     *
     * @return array
     */
    public function getAllChipLabels()
    {
        $labels = [];
        
        $chips = $this->getChips();
        
        foreach ($chips as $chip) {
            
            $labels[$chip] = Chip::label($chip);
        }
        
        return $labels;
    }

    /**
     * Returns all chips they stored in the gpio base path.
     *
     * @return array
     */
    public function getChips()
    {
        $stream = new Stream();
        
        $dir = $stream->getBase();
        
        $chips = preg_match('/gpiochip/', scandir($dir));
        
        if (count($chips) == 0) {
            
            throw new SystemException("Cant find any GPIO chip.");
        }
        
        return (array) $chips;
    }

    /**
     *
     * @return mixed
     */
    public function getFirstChip()
    {
        $chips = $this->getChips();
        
        return $chips[0];
    }
}