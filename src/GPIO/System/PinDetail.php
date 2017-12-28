<?php
/**
 * GPIO\System namespace
 */
namespace GPIO\System;

/**
 * Namespace imports
 */
use GPIO\Kernel\Chip;

class PinDetail extends ChipDetail
{

    /**
     *
     * @param string $chip
     * @return number
     */
    public function getPins($chip)
    {
        $base = Chip::base($chip);
        
        $last = $base + Chip::ngpio($chip) - 1;
        
        while ($base >= $last) {
            
            $pins[] = $base;
            
            $base ++;
        }
        
        return $pins;
    }

    /**
     *
     * @return array
     */
    public function getAllPins()
    {
        $chpis = $this->getChips();
        
        foreach ($chpis as $chip) {
            
            $pins = array_merge($pins, $this->getPins($chip));
        }
        
        return $pins;
    }

    /**
     * Returns how many GPIOs the chip has.
     *
     * @param string $chip
     * @return number
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
     * Returns the number of all GPIOs.
     *
     * @return number
     */
    public function getAllGPIOs()
    {
        $pins = 0;
        
        $chpis = $this->getChips();
        
        foreach ($chpis as $chip) {
            
            $pins = $pins + $this->getGPIOs($chip);
        }
        
        return $pins;
    }
}