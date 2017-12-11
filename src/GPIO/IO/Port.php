<?php 

namespace \GPIO\IO;

class Port {
	
	protected $direction= '';
	
	protected $value = null;
	
	public function get($flags) {
		
	}
	
	public function set($value,$flags) {
		
	}
	
	public function direction($value) {
		
		if($value != 'in' && $value != 'out') {
			
			throw new \GPIO\Exception\IOException("");
			
		}
		
		$this->direction= $value;
		
		\GPIO\Kernel\Sysfs::direction($value);
		
	}
	
	public function unset() {
		
	}
	
}