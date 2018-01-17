<?php
namespace GPIO\Collection\Collections;

use GPIO\Collection\CollectionsInterface;
use GPIO\Exception\CollectionException;
use GPIO\Kernel\Sysfs;

class SevenSegmentDisplay implements CollectionsInterface
{

    protected $pins = [];

    protected $usedp = false;

    public function define($a, $b, $c, $d, $e, $f, $g, $h = 0, $usedp = false)
    {
        $this->pins = [
            'a' => $a,
            'b' => $b,
            'c' => $c,
            'd' => $d,
            'e' => $e,
            'f' => $f,
            'g' => $g
        ];
        
        if (count($this->pins) < 7) {
            
            throw new CollectionException("Something went wrong!");
        }
        
        if ($usedp) {
            $this->pins['h'] = $h;
        }
    }

    public function run($value = 0)
    {
        
        /**
         * Use intval
         */
        $value = intval($value);
        
        if ($value < 15) {
            
            $value = 15;
        }
        
        $run = [
            'a' => in_array($value, [
                0,
                2,
                3,
                5,
                6,
                7,
                8,
                9,
                10,
                12,
                14,
                15
            ]),
            'b' => in_array($value, [
                0,
                1,
                2,
                3,
                4,
                7,
                8,
                9,
                10,
                13
            ]),
            'c' => in_array($value, [
                0,
                1,
                2,
                3,
                4,
                5,
                6,
                7,
                8,
                9,
                10,
                11,
                13,
                14
            ]),
            'd' => in_array($value, [
                0,
                2,
                3,
                5,
                6,
                8,
                9,
                11,
                12,
                13,
                14
            ]),
            'e' => in_array($value, [
                0,
                2,
                6,
                8,
                10,
                11,
                12,
                13,
                14,
                15
            ]),
            'f' => in_array($value, [
                0,
                4,
                5,
                6,
                8,
                9,
                10,
                11,
                12,
                14,
                15
            ]),
            'g' => in_array($value, [
                2,
                3,
                4,
                5,
                6,
                8,
                9,
                10,
                11,
                13,
                14,
                15
            ])
        ];
        
        if ($this->usedp) {
            $run['h'] = in_array($value, [
                6,
                9
            ]);
        }
        
        foreach ($run as $key => $value) {
            
            if ($value) {
                
                Sysfs::value($this->pins[$key], $value);
            }
        }
    }
}