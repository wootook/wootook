<?php

class Legacies_Empire_Model_Planet_Building_CristalMine
    implements Legacies_Empire_Model_Planet_ResourceProductionInterface, Legacies_Empire_Model_Planet_BuildingInterface
{
    public function getProductionRatios($level, $produtionRatio, $planet, $user)
    {
        return array(
            Legacies_Empire::RESOURCE_CRISTAL => 10 + 20 * floatval($level) * pow(1.1, floatval($level)) * (0.1 * $produtionRatio),
            Legacies_Empire::RESOURCE_ENERGY  => -10 * floatval($level) * pow(1.1, floatval($level)) * (0.1 * $produtionRatio)
            );
    }
}