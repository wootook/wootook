<?php

interface Wootook_Empire_Model_Planet_ShipInterface
{
    public function getBaseSpeed(Wootook_Empire_Model_User $user);
    public function getSpeedMultiplier(Wootook_Empire_Model_User $user);
    public function getActualSpeed(Wootook_Empire_Model_User $user);

    public function getBaseConsumption(Wootook_Empire_Model_User $user);
    public function getActualConsumption(Wootook_Empire_Model_User $user, $speed);
}