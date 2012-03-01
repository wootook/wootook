<?php
/**
 * This file is part of Wootook
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.txt
 * @see http://wootook.org/
 *
 * Copyright (c) 2011-Present, Grégory PLANCHAT <g.planchat@gmail.com>
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing Wootook.
 *
 */
/**
 *
 * Enter description here ...
 * @author Greg
 *
 */
class Legacies_Empire_Model_Planet_Ship_Supernova
    extends Wootook_Empire_Model_Planet_ShipAbstract
    implements Wootook_Empire_Model_Planet_ResourceProductionInterface
{
    const REQUIREMENT_TO_UPGRADE_CELERITY__RESEARCH_HYPERSPACE_DRIVE      = 20;
    const REQUIREMENT_TO_UPGRADE_CELERITY__RESEARCH_HYPERSPACE_TECHNOLOGY = 15;

    public function getProductionRatios($quantity, $produtionRatio, $planet, $player)
    {
        return array(
            Legacies_Empire::RESOURCE_ENERGY => ((floatval($planet->getData('temp_max')) / 4) + 20) * (0.1 * $produtionRatio) * floatval($quantity) * -1250
            );
    }

    public function getBaseSpeed(Wootook_Player_Model_Entity $player)
    {
        $prices = Wootook_Empire_Helper_Config_Prices::getSingleton();

        if ($player->getElement(Legacies_Empire::ID_RESEARCH_HYPERSPACE_DRIVE) < self::REQUIREMENT_TO_UPGRADE_CELERITY__RESEARCH_HYPERSPACE_DRIVE &&
            $player->getElement(Legacies_Empire::ID_RESEARCH_HYPERSPACE_TECHNOLOGY) < self::REQUIREMENT_TO_UPGRADE_CELERITY__RESEARCH_HYPERSPACE_TECHNOLOGY) {
            return $prices[Legacies_Empire::ID_SHIP_SUPERNOVA][Legacies_Empire::SHIPS_CELERITY_PRIMARY];
        } else {
            return $prices[Legacies_Empire::ID_SHIP_SUPERNOVA][Legacies_Empire::SHIPS_CELERITY_SECONDARY];
        }
    }

    public function getSpeedMultiplier(Wootook_Player_Model_Entity $player)
    {
        if ($player->getElement(Legacies_Empire::ID_RESEARCH_HYPERSPACE_DRIVE) < self::REQUIREMENT_TO_UPGRADE_CELERITY__RESEARCH_HYPERSPACE_DRIVE &&
            $player->getElement(Legacies_Empire::ID_RESEARCH_HYPERSPACE_TECHNOLOGY) < self::REQUIREMENT_TO_UPGRADE_CELERITY__RESEARCH_HYPERSPACE_TECHNOLOGY) {
            return pow(1.2, $player->getElement(Legacies_Empire::ID_RESEARCH_IMPULSE_DRIVE));
        } else {
            return pow(1.3, $player->getElement(Legacies_Empire::ID_RESEARCH_HYPERSPACE_DRIVE)) *
                pow(1.1, $player->getElement(Legacies_Empire::ID_RESEARCH_HYPERSPACE_TECHNOLOGY));
        }
    }

    public function getBaseConsumption(Wootook_Player_Model_Entity $player)
    {
        return 0;
    }
}