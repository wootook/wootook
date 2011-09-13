<?php

class Legacies_Empire_Model_Planet_Building_RoboticFactory
    implements Legacies_Empire_Model_Planet_BuildingInterface
{
    public static function buildingTimeListener($event)
    {
        $planet = $event->getData('planet');

        if (!$planet->getId() || ($level = $planet->getElement(Legacies_Empire::ID_BUILDING_ROBOTIC_FACTORY)) <= 0) {
            return;
        }

        $time = $event->getData('time');

        $speedFactor = 1 + $level;
        $event->setData('time', $time / $speedFactor);
    }
}