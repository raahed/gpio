<?php 

namespace GPIO\Kernel;

class Sysfs extends Chip {
	
	static public function export($port) {
		
		$stream = new \GPIO\File\Stream('export');
		
		$stream->write($port, true);
		
	}
	
	static public function unexport($port) {
		
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