<?php

namespace GPIO\Kernel;

use GPIO\File\Stream;
use GPIO\Exception\FileException;
use GPIO\Exception\KernelException;

class Chip {

    static protected $chip = '';
    
    static public function base($chip) {
        
        if($chip) {
            
            self::setChip($chip);
            
        }
        
        if(self::$chip == '') {
            
            throw new KernelException("No chip locate! Use setChip()");
            
        }
        
        $stream = new Stream(self::$chip.'/base', Stream::FLAG_STREAM_READ);
        
        return $stream->read(true);
        
    }
    
    static public function label($chip) {
 
        if($chip) {
            
            self::setChip($chip);
            
        }
        
        if(self::$chip == '') {
            
            throw new KernelException("No chip locate! Use setChip()");
            
        }
        
        $stream = new Stream(self::$chip.'/label', Stream::FLAG_STREAM_READ);
        
        return $stream->read(true);
        
    }
    
    static public function ngpio($chip) {
        
        if($chip) {
            
            self::setChip($chip);
            
        }
        
        if(self::$chip == '') {
            
            throw new KernelException("No chip locate! Use setChip()");
            
        }
        
        $stream = new Stream(self::$chip.'/ngpio', Stream::FLAG_STREAM_READ);
        
        return $stream->read(true);
        
    }
    
    static public function getChip() {
        
        return self::$chip;
        
    }
    
    static public function setChip($chip) {
               
        if(!$chip) {
            
            return;
            
        }
        
        $stream = new Stream();
        
        $base = $stream->getBase();
        
        if(is_dir($base.'/'.$chip)) {
            
            self::$chip = $chip;
            
        } else {
            
            throw new FileException("Cant find Chip by: ".$base.'/'.$chip);
            
        }
        
        $stream->close();
        
    }
    
    static public function unsetChip() {
        
        self::$chip = '';
        
    }

}