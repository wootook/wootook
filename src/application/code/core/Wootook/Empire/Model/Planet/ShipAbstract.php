<?php

abstract class Wootook_Empire_Model_Planet_ShipAbstract
    implements Wootook_Empire_Model_Planet_ShipInterface
{
    public function getBaseSpeed(Wootook_Player_Model_Entity $player)
    {
        $prices = Wootook_Empire_Helper_Config_Prices::getSingleton();

        return $prices[Legacies_Empire::ID_SHIP_SUPERNOVA][Legacies_Empire::SHIPS_CELERITY_PRIMARY];
    }

    public function getSpeedMultiplier(Wootook_Player_Model_Entity $player)
    {
        return pow(1.1, $player->getElement(Legacies_Empire::ID_RESEARCH_COMBUSTION_DRIVE));
    }

    public function getActualSpeed(Wootook_Player_Model_Entity $player)
    {
        return $this->getBaseSpeed($player) * $this->getSpeedMultiplier($player);
    }

    public function getActualConsumption(Wootook_Player_Model_Entity $player, $speed)
    {
        return $this->getBaseConsumption($player) * ($speed / 100) * $this->getSpeedMultiplier($player);
    }
}