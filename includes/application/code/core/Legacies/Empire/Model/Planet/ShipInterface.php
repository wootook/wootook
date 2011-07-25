<?php

interface Legacies_Empire_Model_Planet_ShipInterface
{
    public function getBaseSpeed(Legacies_Empire_Model_User $user);
    public function getSpeedMultiplier(Legacies_Empire_Model_User $user);
    public function getActualSpeed(Legacies_Empire_Model_User $user);

    public function getBaseConsumption(Legacies_Empire_Model_User $user);
    public function getActualConsumption(Legacies_Empire_Model_User $user, $speed);
}