<?php

class Legacies_Empire_Model_Planet_Building_NaniteFactory
    implements Legacies_Empire_Model_Planet_BuildingInterface
{
    public static function buildingTimeListener($event)
    {
        $planet = $event->getData('planet');

        if (!$planet->getId() || ($level = $planet->getElement(Legacies_Empire::ID_BUILDING_NANITE_FACTORY)) <= 0) {
            return;
        }

        $time = $event->getData('time');

        $speedFactor = pow(2, $level);
        $event->setData('time', $time / $speedFactor);
    }
}