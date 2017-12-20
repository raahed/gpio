<?php 

namespace GPIO\IO;

use GPIO\Exception\IOException;
use GPIO\Kernel\Sysfs;

class Port {
	
	protected $direction= '';
	
	protected $value = null;
	
	public function get($flags) {
		
	}
	
	public function set($value,$flags) {
		
	}
	
	public function direction($value) {
		
		if($value != 'in' && $value != 'out') {
			
			throw new IOException("");
			
		}
		
		$this->direction= $value;
		
		Sysfs::direction($value);
		
	}
	
	public function reset() {
		
	}
	
}