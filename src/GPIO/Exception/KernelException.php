<?php 

namespace GPIO\Exception;

class KernelException extends \Exception {
	
    public function __construct($message) {
        
        parent::__construct($message);
        
    }
    
}