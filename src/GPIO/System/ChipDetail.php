<?php
/**
 * GPIO\System namespace
 */
namespace GPIO\System;

/**
 * Namespace imports
 */
use GPIO\Kernel\Chip;
use GPIO\File\Directory;
use GPIO\Exception\SystemException;

/**
 * Chip helper class.
 * Some useful functions.
 *
 * @author raah
 */
class ChipDetail
{

    /**
     * Gets all GPIO Pins from a single chip.
     *
     * @param string $chip
     * @return int
     */
    public function getGPIOs($chip)
    {
        Chip::setChip($chip);
        
        $base = Chip::base();
        $ngpio = Chip::ngpio();
        
        /**
         * This calculation based on the kernel documentation.
         *
         * @see https://www.kernel.org/doc/Documentation/gpio/sysfs.txt
         * @var Ambiguous $pins
         */
        $pins = $base - $base + $ngpio - 1;
        
        return $pins;
    }

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
            
            $labels[] = Chip::label($chip);
        }
        
        return $labels;
    }

    /**
     * Gets all GPIO Pins based on the chips.
     *
     * @return number
     */
    public function getAllGPIOPins()
    {
        $pins = 0;
        
        $chpis = $this->getChips();
        
        foreach ($chpis as $chip) {
            
            $pins = $pins + $this->getGPIOs($chip);
        }
        
        return $pins;
    }

    /**
     * Returns all chips they stored in the gpio base path.
     *
     * @return array
     */
    public function getChips()
    {
        $dir = new Directory();
        
        $chips = $dir->get()->pattern('gpiochip*');
        
        if(count($chips) == 0) {
            
            throw new SystemException("Cant find any GPIO chip.");
        }
        
        return (array) $chips;
    }
}