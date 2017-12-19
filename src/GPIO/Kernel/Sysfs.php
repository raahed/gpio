<?php 

namespace GPIO\Kernel;

class Sysfs extends Chip {
	
	static public function export($port) {

        if(!$port) {
            
            return;
            
        }
        
		$stream = new \GPIO\File\Stream('export', \GPIO\File\Stream::FLAG_STREAM_WRITE);
		
		$stream->write($port, true);
	
	}
	
	static public function unexport($port) {

        if(!$port) {
            
            return;
            
        }

		$stream = new \GPIO\File\Stream('unexport', \GPIO\File\Stream::FLAG_STREAM_WRITE);
		
		$stream->write($port, true);
	
	}
	
	static public function direction($port, $value) {

        if(!$port) {
            
            return;
            
        }

		$stream = new \GPIO\File\Stream('gpio'.$port.'/direction', \GPIO\File\Stream::FLAG_STREAM_WRITE);
		
		$stream->write($value, true);
		
	}
	
	static public function value($port,$value) {
	    
        if(!$port) {
            
            return;
            
        }

        #TODO: check binary!

        if($value) {
            
            $stream = new \GPIO\File\Stream('gpio'.$port.'/value', \GPIO\File\Stream::FLAG_STREAM_WRITE);
		
		    $stream->write($value,true);	  
            
            
        } else {
            
            $stream = new \GPIO\File\Stream('gpio'.$port.'/value', \GPIO\File\Stream::FLAG_STREAM_READ);
		
		    return $stream->read(true);	  
            
        }
	    
	}
	
	static public function edge() {
	    
	}
	
	static public function active_low() {
	    
	}
	
}