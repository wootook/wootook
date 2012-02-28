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
class Legacies_Empire_Model_Planet_Ship_SolarSatellite
    extends Wootook_Empire_Model_Planet_ShipAbstract
    implements Wootook_Empire_Model_Planet_ResourceProductionInterface
{
    public function getProductionRatios($quantity, $produtionRatio, $planet, $player)
    {
        return array(
            Legacies_Empire::RESOURCE_ENERGY => ((floatval($planet->getData('temp_max')) / 4) + 20) * (0.1 * $produtionRatio) * floatval($quantity)
            );
    }

    public function getBaseSpeed(Wootook_Player_Model_Entity $player)
    {
        return 0;
    }

    public function getSpeedMultiplier(Wootook_Player_Model_Entity $player)
    {
        return 0;
    }

    public function getActualSpeed(Wootook_Player_Model_Entity $player)
    {
        return 0;
    }

    public function getBaseConsumption(Wootook_Player_Model_Entity $player)
    {
        return 0;
    }
}