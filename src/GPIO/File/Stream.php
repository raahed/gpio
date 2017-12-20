<?php 

namespace GPIO\File;

use GPIO\Exception\FileException;

class Stream {
	
	const FLAG_STREAM_WRITE = 100;
	
	const FLAG_STREAM_READ = 010;
	
	const FLAG_STREAM_PERM = 001;
	
	protected $base = '/sys/class/gpio';
	
	protected $mode = '';
	
	protected $stream = null;
	
	public function __construct($context,$flags) {
	    
	    if($context) {
	        
	        $this->open($context,$flags);
	    
	    }
	    
	}
	
	public function __destruct() {
	    
	    if($this->stream) {
	        
	        $this->close();
	        
	    }
	    
	}
	
	public function __toString() {
	    
	    if($this->stream) {
	     
	        return $this->read(true);
	        
	    }
	    
	}
	
	public function open($context,$flags = 000) {
	    
	    if(!$context) {
	        
	        throw new FileException("Missing context in file stream.");
	        
	    }
	    
	    if($flags & self::FLAG_STREAM_READ) {
	        
	        $this->mode = 'r';
	        
	    } elseif($flags & self::FLAG_STREAM_WRITE) {
	        
	        $this->mode = 'w';
	        
	    } else {
	        
	        $this->mode = 'w+';
	        
	    }
	    
	    if(strpos($context,'/') != 0) {
	        
	        $file = $this->base.'/'.$context;
	        
	    } else {
	        
	        $file = $this->base.$context;
	        
	    }
	    
	    try {
	        
	        /**
	         * include base = flase 
	         */
	        $this->stream = fopen($file,$this->mode,false);
	        
	    } catch(\Exception $e) {
	        
	        throw new FileException("Open stream context failed: ".$e->getMessage());
	        
	    }
	    
	    
	}
	
	public function close() {

	    if($this->stream) {

            fclose($this->stream);
            
            if($this->stream) {
                
                $this->stream = null;
                
            }
            
            $this->mode = '';
	        
	    }
	    
	}
	
	public function read($close = false) {
	    
	    if($this->stream) {
	        
	        $buffer = fread($this->stream);
	        
	        if($close == true) {
	            
	            $this->close;
	            
	        }
	        
	        return $buffer;
	    
	    }	 
	    
	}
	
	public function write($content = '', $close = false) {
	    
	    if($this->stream) {
	        
	        if($this->mode == 'r') {
	            
	            throw new \GPIO\Exception\FileException("Try to write something to a read-only stream.");
	            
	        }
	        
	        fwrite($this->stream,$content);
	        
	        if($close == true) {
	            
	            $this->close();
	            
	        }
	        
	    }
	    
	}
	
	public function getBase() {
	    
	    return $this->base;
	    
	}
	
	public function getMode() {
	    
	    return $this->mode;
	    
	}
	
}