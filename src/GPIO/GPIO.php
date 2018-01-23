<?php
namespace GPIO;

use GPIO\System\PinDetail;

class GPIO
{

    protected $useablepins = [];

    protected $usedchips = [];

    public function __construct($setup = true)
    {
        if ($setup) {
            
            $this->setup();
        }
    }

    protected function setup()
    {
        $this->updateUseablePins();
        
        $this->updateChips();
    }

    public function updateUseablePins()
    {
        $system = new PinDetail();
        
        $this->useablepins = $system->getAllPins();
    }

    public function updateChips()
    {
        $system = new PinDetail();
        
        $this->usedchips = $system->getChips();
    }

    public function getUseablePins()
    {
        return $this->useablepins;
    }

    public function getUsedChips()
    {
        return $this->usedchips;
    }
}