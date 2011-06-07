<?php

class Legacies_Empire_Model_Planet_Ship_Supernova
    implements Legacies_Empire_Model_Planet_ResourceProductionInterface, Legacies_Empire_Model_Planet_ShipInterface
{
    public function getProductionRatios($quantity, $produtionRatio, $planet, $user)
    {
        return array(
            Legacies_Empire::RESOURCE_ENERGY => ((floatval($planet->getData('temp_max')) / 4) + 20) * (0.1 * $produtionRatio) * floatval($quantity) * -1250
            );
    }
}