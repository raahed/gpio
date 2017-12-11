<?php

namespace GPIO;


final class GPIO extends \GPIO\Kernel\Sysfs {

    protected $ports = [];

    public function port($number = 0) {
        
        if(!$this->ports[$number]) {
            
            self::export($number);
            
            $this->ports[$number] = new \GPIO\IO\Port();
            
        }
        
        return $this->ports[$number];
        
    }
    
}