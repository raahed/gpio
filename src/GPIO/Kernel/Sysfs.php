<?php 

namespace GPIO\Kernel;

use GPIO\File\Stream;

class Sysfs extends Chip {
	
	static public function export($port) {

        if(!$port) {
            
            return;
            
        }
        
		$stream = new Stream('export', Stream::FLAG_STREAM_WRITE);
		
		$stream->write($port, true);
	
	}
	
	static public function unexport($port) {

        if(!$port) {
            
            return;
            
        }

		$stream = new Stream('unexport', Stream::FLAG_STREAM_WRITE);
		
		$stream->write($port, true);
	
	}
	
	static public function direction($port, $value) {

        if(!$port) {
            
            return;
            
        }

		$stream = new Stream('gpio'.$port.'/direction', Stream::FLAG_STREAM_WRITE);
		
		$stream->write($value, true);
		
	}
	
	static public function value($port,$value) {
	    
        if(!$port) {
            
            return;
            
        }

        #TODO: check binary!

        if($value) {
            
            $stream = new Stream('gpio'.$port.'/value', Stream::FLAG_STREAM_WRITE);
		
		    $stream->write($value,true);	  
            
            
        } else {
            
            $stream = new Stream('gpio'.$port.'/value', Stream::FLAG_STREAM_READ);
		
		    return $stream->read(true);	  
            
        }
	    
	}
	
	static public function edge() {
	    
	}
	
	static public function active_low() {
	    
	}
	
}