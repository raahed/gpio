<?php 

namespace GPIO\Exception;

class FileException extends \Exception {
	
    public function __construct($message) {
        
        parent::__construct($message);
        
    }
    
}