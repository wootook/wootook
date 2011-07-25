<?php

class Legacies_Empire_Model_Planet_Ship_Supernova
    extends Legacies_Empire_Model_Planet_ShipAbstract
    implements Legacies_Empire_Model_Planet_ResourceProductionInterface
{
    const REQUIREMENT_TO_UPGRADE_CELERITY__RESEARCH_HYPERSPACE_DRIVE      = 20;
    const REQUIREMENT_TO_UPGRADE_CELERITY__RESEARCH_HYPERSPACE_TECHNOLOGY = 15;

    public function getProductionRatios($quantity, $produtionRatio, $planet, $user)
    {
        return array(
            Legacies_Empire::RESOURCE_ENERGY => ((floatval($planet->getData('temp_max')) / 4) + 20) * (0.1 * $produtionRatio) * floatval($quantity) * -1250
            );
    }

    public function getBaseSpeed(Legacies_Empire_Model_User $user)
    {
        $prices = Legacies_Empire_Model_Game_Prices::getSingleton();

        if ($user->getElement(Legacies_Empire::ID_RESEARCH_HYPERSPACE_DRIVE) < self::REQUIREMENT_TO_UPGRADE_CELERITY__RESEARCH_HYPERSPACE_DRIVE &&
            $user->getElement(Legacies_Empire::ID_RESEARCH_HYPERSPACE_TECHNOLOGY) < self::REQUIREMENT_TO_UPGRADE_CELERITY__RESEARCH_HYPERSPACE_TECHNOLOGY) {
            return $prices[Legacies_Empire::ID_SHIP_SUPERNOVA][Legacies_Empire::SHIPS_CELERITY_PRIMARY];
        } else {
            return $prices[Legacies_Empire::ID_SHIP_SUPERNOVA][Legacies_Empire::SHIPS_CELERITY_SECONDARY];
        }
    }

    public function getSpeedMultiplier(Legacies_Empire_Model_User $user)
    {
        if ($user->getElement(Legacies_Empire::ID_RESEARCH_HYPERSPACE_DRIVE) < self::REQUIREMENT_TO_UPGRADE_CELERITY__RESEARCH_HYPERSPACE_DRIVE &&
            $user->getElement(Legacies_Empire::ID_RESEARCH_HYPERSPACE_TECHNOLOGY) < self::REQUIREMENT_TO_UPGRADE_CELERITY__RESEARCH_HYPERSPACE_TECHNOLOGY) {
            return pow(1.2, $user->getElement(Legacies_Empire::ID_RESEARCH_IMPULSE_DRIVE));
        } else {
            return pow(1.3, $user->getElement(Legacies_Empire::ID_RESEARCH_HYPERSPACE_DRIVE)) *
                pow(1.1, $user->getElement(Legacies_Empire::ID_RESEARCH_HYPERSPACE_TECHNOLOGY));
        }
    }
}