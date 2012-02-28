<?php

class Wootook_Empire_Block_Planet_Overview
    extends Wootook_Core_Block_Template
{
    protected $_planet = null;

    public function getPlayer()
    {
        return Wootook_Player_Model_Session::getSingleton()
            ->getPlayer();
    }

    /**
     *
     * @return Wootook_Empire_Model_Planet
     */
    public function getPlanet()
    {
        if ($this->_planet === null) {
            $this->_planet = $this->getPlayer()->getCurrentPlanet();
        }
        return $this->_planet;
    }

    public function getPlanetName()
    {
        return $this->getPlanet()->getName();
    }

    public function getPlanetCoords()
    {
        return $this->getPlanet()->getCoords();
    }

    public function getPlanetImage()
    {
        return $this->getPlanet()->getImage();
    }
}