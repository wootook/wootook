<?php

class Legacies_Empire_Model_Planet_Ship_LightTransport
    implements Legacies_Empire_Model_Planet_ShipInterface
{
    const REQUIREMENT_TO_UPGRADE_CELERITY__RESEARCH_IMPULSE_DRIVE = 4;

    public function getBaseSpeed(Legacies_Empire_Model_User $user)
    {
        $prices = Legacies_Empire_Model_Game_Prices::getSingleton();

        if ($user->getElement(Legacies_Empire::ID_RESEARCH_IMPULSE_DRIVE) < self::REQUIREMENT_TO_UPGRADE_CELERITY__RESEARCH_IMPULSE_DRIVE) {
            return $prices[Legacies_Empire::ID_SHIP_LIGHT_TRANSPORT][Legacies_Empire::SHIPS_CELERITY_PRIMARY];
        } else {
            return $prices[Legacies_Empire::ID_SHIP_LIGHT_TRANSPORT][Legacies_Empire::SHIPS_CELERITY_SECONDARY];
        }
    }

    public function getSpeedMultiplier(Legacies_Empire_Model_User $user)
    {
        if ($user->getElement(Legacies_Empire::ID_RESEARCH_HYPERSPACE_DRIVE) < self::REQUIREMENT_TO_UPGRADE_CELERITY__RESEARCH_IMPULSE_DRIVE) {
            return pow(1.1, $user->getElement(Legacies_Empire::ID_RESEARCH_COMBUSTION_DRIVE));
        } else {
            return pow(1.2, $user->getElement(Legacies_Empire::ID_RESEARCH_IMPULSE_DRIVE));
        }
    }
}