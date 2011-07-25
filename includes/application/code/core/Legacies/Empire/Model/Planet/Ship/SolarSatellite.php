<?php

class Legacies_Empire_Model_Planet_Ship_SolarSatellite
    extends Legacies_Empire_Model_Planet_ShipAbstract
    implements Legacies_Empire_Model_Planet_ResourceProductionInterface
{
    public function getProductionRatios($quantity, $produtionRatio, $planet, $user)
    {
        return array(
            Legacies_Empire::RESOURCE_ENERGY => ((floatval($planet->getData('temp_max')) / 4) + 20) * (0.1 * $produtionRatio) * floatval($quantity)
            );
    }

    public function getBaseSpeed(Legacies_Empire_Model_User $user)
    {
        return 0;
    }

    public function getSpeedMultiplier(Legacies_Empire_Model_User $user)
    {
        return 0;
    }

    public function getActualSpeed(Legacies_Empire_Model_User $user)
    {
        return 0;
    }

    public function getBaseConsumption(Legacies_Empire_Model_User $user)
    {
        return 0;
    }
}