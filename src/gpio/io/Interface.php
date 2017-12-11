<?php


namespace GPIO\IO;

interface IOInterface {
    
    protected $value;
    
    public function __construct();
    
    protected function __callStatic();
    
    public function __clone();
    
    public function __toString();
    
    public function __dectruct();

}