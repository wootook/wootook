<?php

class Legacies_Empire_Model_Planet_Ship_LargeTransport
    extends Legacies_Empire_Model_Planet_ShipAbstract
{
    const REQUIREMENT_TO_UPGRADE_CELERITY__RESEARCH_IMPULSE_DRIVE = 4;

    public function getBaseSpeed(Legacies_Empire_Model_User $user)
    {
        $prices = Legacies_Empire_Model_Game_Prices::getSingleton();

        return $prices[Legacies_Empire::ID_SHIP_LARGE_TRANSPORT][Legacies_Empire::SHIPS_CELERITY_PRIMARY];
    }

    public function getSpeedMultiplier(Legacies_Empire_Model_User $user)
    {
        return pow(1.1, $user->getElement(Legacies_Empire::ID_RESEARCH_COMBUSTION_DRIVE));
    }
}