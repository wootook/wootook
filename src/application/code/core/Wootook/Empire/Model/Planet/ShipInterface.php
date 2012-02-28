<?php

interface Wootook_Empire_Model_Planet_ShipInterface
{
    public function getBaseSpeed(Wootook_Player_Model_Entity $player);
    public function getSpeedMultiplier(Wootook_Player_Model_Entity $player);
    public function getActualSpeed(Wootook_Player_Model_Entity $player);

    public function getBaseConsumption(Wootook_Player_Model_Entity $player);
    public function getActualConsumption(Wootook_Player_Model_Entity $player, $speed);
}