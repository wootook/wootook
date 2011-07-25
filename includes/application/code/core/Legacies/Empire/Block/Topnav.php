<?php

class Legacies_Empire_Block_Topnav
    extends Legacies_Core_Block_Template
{
    /**
     *
     * @return Legacies_Empire_Model_User
     */
    public function getCurrentUser()
    {
        return Legacies_Empire_Model_User::getSingleton();
    }

    /**
     *
     * @return Legacies_Empire_Model_Planet
     */
    public function getCurrentPlanet()
    {
        return $this->getCurrentUser()->getCurrentPlanet();
    }

    /**
     *
     * @return Legacies_Core_Collection
     */
    public function getPlanetCollection()
    {
        return $this->getCurrentUser()->getPlanetCollection();
    }
}