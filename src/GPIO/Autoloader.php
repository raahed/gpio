<?php

/**
 * GPIO namespace
 */
namespace \GPIO;

/**
 * U can run the GPIO stuff without the
 * composer. Call the autoloader class likes
 * Autoloader::instance()->setup() before
 * your code.
 * 
 * @author raah
 * @link http://php.net/manual/en/function.spl-autoload-register.php
 */
class Autoloader {
	
	/**
	 * Stores the source path name.
	 * 
	 * @var string
	 */
	protected $dir = '';
	
	/**
	 * This value will be true when the
	 * autoloader runnnnnns.
	 * 
	 * @var string
	 */
	protected $run = false;
	
	/**
	 * Here lives the singleton instance.
	 * 
	 * @var  Autoloader
	 */
	private static $instance = null;
	
	/**
	 * Setup the autoloader and 
	 * register the function.
	 * 
	 * @param string $dir
	 */
	public function setup($dir = __DIR__) {
		
		if($this->run) {
			
			return;
			
		}
		
		$this->dir = $dir;
		
		/**
		 * Register the autoloader.
		 * Requires PHP v5.1.2! 
		 */
		spl_autoload_register(array($this, 'autoload'));
		
		$this->run = true;
		
	}
	
	/**
	 * Autoload function pretty sick.
	 * 
	 * @param string $class
	 * @throws ErrorException
	 * @return boolean
	 */
	public function autoload($class) {
		
		$filename = $this->translateClassToFile($class);
		
		if(!file_exists($filename) || !$filename) {
			
			throw new Exception("Cant load class with the name: ".$class);
			
		}
		
		include($filename);
		
		if(class_exists($class,false) || interface_exists($class,false)) {
			
			return true;
			
		}
		
	}
	
	/**
	 * Translate the class name to the filename
	 * uses the str_replace function.
	 * 
	 * @param string $class
	 * @return void|string
	 */
	protected function translateClassToFile($class) {
		
		if(!$class) {
			
			return;
			
		}
		
		$file = str_replace('\\', '/', $class);
		
		return $this->dir.'/'.$file;
		
		
		
	}
	
	/**
	 * The final function returns the singleton instance.
	 * 
	 * @return object
	 */
	static public final function instance() {
		
		if(!self::$instance) {
			
			self::$instance = new self();
			
		}
		
		return self::$instance;
		
	}
	
}