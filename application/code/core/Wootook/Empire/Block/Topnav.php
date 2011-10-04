<?php

class Wootook_Empire_Block_Topnav
    extends Wootook_Core_Block_Template
{
    /**
     *
     * @return Wootook_Empire_Model_User
     */
    public function getCurrentUser()
    {
        return Wootook_Empire_Model_User::getSingleton();
    }

    /**
     *
     * @return Wootook_Empire_Model_Planet
     */
    public function getCurrentPlanet()
    {
        return $this->getCurrentUser()->getCurrentPlanet();
    }

    /**
     *
     * @return Wootook_Core_Collection
     */
    public function getPlanetCollection()
    {
        return $this->getCurrentUser()->getPlanetCollection();
    }
}