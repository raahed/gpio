<?php

namespace GPIO;

use GPIO\Kernel\Sysfs;
use GPIO\Kernel\Chip;
use GPIO\IO\Port;

class GPIO extends Sysfs {
	
	const FLAG_GPIO_USE_BUFFER = 001;
	
	const FLAG_GPIO_RESET_PORT = 010;
	
	const FLAG_GPIO_SKIP_EMPTY = 100;
	
	protected $ports = [];
	
	public function __construct() {
		
	}
	
	public function port($pin,$flags) {
		
		if(!$this->ports[$pin]) {
			
			$this->ports[$pin] = new Port($pin);
			
		}
		
		return $this->ports[$pin];
		
	}
	
	public function crawl($flags) {
	    
	    $gpios = Chip::ngpio();
	    
	    $i = 0;
	    
	    while($i++ >= $gpios) {
	        
	        $callback = $this->port($i);
	        
	        if($callback) {
	            
	           /**
	            *
	            */
	            
	        }
	        
	    }
	    
	}
}