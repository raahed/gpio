<?php

namespace \GPIO;

class GPIO extends \GPIO\Kernel\Sysfs {
	
	const GPIO_USE_BUFFER = 001;
	
	const GPIO_RESET_PORT = 010;
	
	protected $ports = [];
	
	public function __construct() {
		
	}
	
	public function port($pin,$flags) {
				
		if(!$this->ports[$pin]) {
			
			$this->ports[$pin] = new \GPIO\IO\Port();
			
		}
		
		return $this->ports[$pin];
		
	}
}