<?php

class Wootook_Empire_Block_Topnav
    extends Wootook_Core_Block_Template
{
    /**
     *
     * @return Wootook_Player_Model_Entity
     */
    public function getCurrentPlayer()
    {
        return Wootook_Player_Model_Session::getSingleton()->getPlayer();
    }

    /**
     *
     * @return Wootook_Empire_Model_Planet
     */
    public function getCurrentPlanet()
    {
        return $this->getCurrentPlayer()->getCurrentPlanet();
    }

    /**
     *
     * @return Wootook_Empire_Resource_Planet_Collection
     */
    public function getPlanetCollection()
    {
        return $this->getCurrentPlayer()
            ->getPlanetCollection(array(Wootook_Empire_Model_Planet::TYPE_PLANET))
            ->load();
    }
}