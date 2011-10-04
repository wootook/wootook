<?php

class Wootook_Empire_Model_Galaxy_Position
    extends Wootook_Core_Entity_SubTable
{
    protected function _init()
    {
        $this->_tableName = 'galaxy';
        $this->_idFieldNames = array('id_planet');
    }

    static function initPlanetListerner($eventData)
    {
        if (!isset($eventData['planet'])) {
            return;
        }

        $planet = $eventData['planet'];
        if (!$planet->isPlanet()) {
            return;
        }

        $galaxy = new self();
        $galaxy
            ->setData('galaxy', $planet->getGalaxy())
            ->setData('system', $planet->getSystem())
            ->setData('planet', $planet->getPosition())
            ->setData('id_planet', $planet->getId())
            ->save()
        ;
    }
}