<?php

abstract class Wootook_Empire_Model_Planet_ShipAbstract
    implements Wootook_Empire_Model_Planet_ShipInterface
{
    public function getBaseSpeed(Wootook_Empire_Model_User $user)
    {
        $prices = Wootook_Empire_Model_Game_Prices::getSingleton();

        return $prices[Legacies_Empire::ID_SHIP_SUPERNOVA][Legacies_Empire::SHIPS_CELERITY_PRIMARY];
    }

    public function getSpeedMultiplier(Wootook_Empire_Model_User $user)
    {
        return pow(1.1, $user->getElement(Legacies_Empire::ID_RESEARCH_COMBUSTION_DRIVE));
    }

    public function getActualSpeed(Wootook_Empire_Model_User $user)
    {
        return $this->getBaseSpeed($user) * $this->getSpeedMultiplier($user);
    }

    public function getActualConsumption(Wootook_Empire_Model_User $user, $speed)
    {
        return $this->getBaseConsumption($user) * ($speed / 100) * $this->getSpeedMultiplier($user);
    }
}