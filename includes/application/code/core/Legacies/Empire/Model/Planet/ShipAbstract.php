<?php

abstract class Legacies_Empire_Model_Planet_ShipAbstract
    implements Legacies_Empire_Model_Planet_ShipInterface
{
    public function getBaseSpeed(Legacies_Empire_Model_User $user)
    {
        $prices = Legacies_Empire_Model_Game_Prices::getSingleton();

        return $prices[Legacies_Empire::ID_SHIP_SUPERNOVA][Legacies_Empire::SHIPS_CELERITY_PRIMARY];
    }

    public function getSpeedMultiplier(Legacies_Empire_Model_User $user)
    {
        return pow(1.1, $user->getElement(Legacies_Empire::ID_RESEARCH_COMBUSTION_DRIVE));
    }

    public function getActualSpeed(Legacies_Empire_Model_User $user)
    {
        return $this->getBaseSpeed($user) * $this->getSpeedMultiplier($user);
    }

    public function getActualConsumption(Legacies_Empire_Model_User $user, $speed)
    {
        return $this->getBaseConsumption($user) * ($speed / 100) * $this->getSpeedMultiplier($user);
    }
}