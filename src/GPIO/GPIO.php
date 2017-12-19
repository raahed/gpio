<?php

namespace \GPIO;

class GPIO extends Kernel\Sysfs {
	
	public const FLAG_GPIO_USE_BUFFER = 001;
	
	public const FLAG_GPIO_RESET_PORT = 010;
	
	public const FLAG_GPIO_SKIP_EMPTY = 100;
	
	protected $ports = [];
	
	public function __construct() {
		
	}
	
	public function port($pin,$flags) {
		
		if(!$this->ports[$pin]) {
			
			$this->ports[$pin] = new \GPIO\IO\Port($pin);
			
		}
		
		return $this->ports[$pin];
		
	}
	
	public function crawl($flags) {
	    
	    $gpios = Kernel\Chip::ngpio();
	    
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