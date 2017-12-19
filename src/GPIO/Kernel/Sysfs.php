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
	
	static public function direction($value) {
		
	}
	
	static public function value($port) {
	    
	}
	
	static public function edge() {
	    
	}
	
	static public function active_low() {
	    
	}
	
}