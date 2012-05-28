<?php

class Wootook_Empire_Resource_Fleet_Collection
    extends Wootook_Core_Resource_EntityCollection
{
    protected function _construct()
    {
        $this->_init('fleets', 'Wootook_Empire_Model_Fleet');
    }

    public function addPlanetToFilter(Wootook_Empire_Model_Planet $planet, $time = null)
    {
        if ($time === null) {
            $time = new Wootook_Core_DateTime();
        }
        $this->addFieldToFilter(null, array(
            array('and' => array(
                array('eq'   => array('field' => 'fleet_start_galaxy', 'value' => $planet->getGalaxy())),
                array('eq'   => array('field' => 'fleet_start_system', 'value' => $planet->getSystem())),
                array('eq'   => array('field' => 'fleet_start_planet', 'value' => $planet->getPosition())),
                array('eq'   => array('field' => 'fleet_start_type', 'value' => $planet->getType())),
                //array('date' => array('field' => 'fleet_start_time', 'value' => array('to' => $time))),
                )),
            array('and' => array(
                array('eq'   => array('field' => 'fleet_end_galaxy', 'value' => $planet->getGalaxy())),
                array('eq'   => array('field' => 'fleet_end_system', 'value' => $planet->getSystem())),
                array('eq'   => array('field' => 'fleet_end_planet', 'value' => $planet->getPosition())),
                array('eq'   => array('field' => 'fleet_end_type', 'value' => $planet->getType())),
                //array('date' => array('field' => 'fleet_end_time', 'value' => array('to' => $time))),
                ))
            ));

        return $this;
    }

    public function addIsVisibleToFilter(Wootook_Player_Model_Entity $player, $time = null)
    {
        if ($time === null) {
            $time = new Wootook_Core_DateTime();
        }
        $this->addFieldToFilter(null, array(
            array('and' => array(
                array('eq'   => array('field' => 'fleet_owner', 'value' => $player->getId())),
                array('date' => array('field' => 'fleet_start_time', 'value' => array('to' => $time))),
                )),
            array('and' => array(
                array('eq'   => array('field' => 'fleet_owner', 'value' => $player->getId())),
                array('date' => array('field' => 'fleet_end_time', 'value' => array('to' => $time))),
                ))
            ));

        return $this;
    }
}
