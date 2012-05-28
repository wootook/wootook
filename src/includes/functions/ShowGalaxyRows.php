<?php
/**
 * This file is part of Wootook
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://wootook.org/
 *
 * Copyright (c) 2009-Present, Wootook Support Team <http://wootook.org>
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
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
 * @deprecated
 * @param int $Galaxy
 * @param int $System
 */
function ShowGalaxyRows($Galaxy, $System)
{
    global $planetcount;
    
    $readAdapter = Wootook_Core_Database_ConnectionManager::getSingleton()->getConnection('core_read');

    $galaxyStatement = $readAdapter->select()
        ->column('*')
        ->from($readAdapter->getTable('galaxy'))
        ->where('galaxy', $Galaxy)
        ->where('system', $System)
        ->where('planet', new Wootook_Core_Database_Sql_Placeholder_Variable('planet'))
        ->limit(1)
        ->prepare()
    ;

    $planetStatement = $readAdapter->select()
        ->column('*')
        ->from($readAdapter->getTable('planets'))
        ->where('id', new Wootook_Core_Database_Sql_Placeholder_Variable('planet_id'))
        ->limit(1)
        ->prepare()
    ;

    $output = "";
    for ($Planet = 1; $Planet <= Wootook::getGameConfig('engine/universe/positions'); $Planet++) {
        if ($galaxyStatement->execute(array('planet' => $Planet))) {
            /** @var Wootook_Empire_Model_Galaxy_Position $galaxyPosition */
            $galaxyPosition = $galaxyStatement->fetchEntity('Wootook_Empire_Model_Galaxy_Position');
        } else {
            $output .= '<tr></tr>';
            continue;
        }

        $output .= "<tr>";
        if ($galaxyPosition->getData('galaxy') && $galaxyPosition["id_planet"] != 0) {
            if ($planetStatement->execute(array('planet_id' => $galaxyPosition["id_planet"]))) {
                /** @var Wootook_Empire_Model_Planet $currentPlanet */
                $currentPlanet = $planetStatement->fetchEntity('Wootook_Empire_Model_Planet');
            } else {
                $currentPlanet = new Wootook_Empire_Model_Planet();
            }

            if ($currentPlanet->getId() && !$currentPlanet->isDestroyed()) {
                $planetcount++;
                $currentPlayer = $currentPlanet->getPlayer();
            } else {
                CheckAbandonPlanetState($currentPlanet);
                $currentPlayer = new Wootook_Player_Model_Entity();
            }

            if ($galaxyPosition["id_luna"] != 0) {
                if ($planetStatement->execute(array('planet_id' => $galaxyPosition["id_luna"]))) {
                    /** @var Wootook_Empire_Model_Planet $currentMoon */
                    $currentMoon = $planetStatement->fetchEntity('Wootook_Empire_Model_Planet');
                } else {
                    $currentMoon = new Wootook_Empire_Model_Planet();
                }

                if ($currentMoon->isDestroyed()) {
                    CheckAbandonMoonState($currentMoon);
                }
            } else {
                $currentMoon = new Wootook_Empire_Model_Planet();
            }
        } else {
            $currentPlanet = new Wootook_Empire_Model_Planet();
            $currentPlayer = new Wootook_Player_Model_Entity();
            $currentMoon = new Wootook_Empire_Model_Planet();
        }
        $output .= GalaxyRowPos($Planet, $galaxyPosition);
        $output .= GalaxyRowPlanet($galaxyPosition, $currentPlanet, $currentPlayer, $Galaxy, $System, $Planet, 1);
        $output .= GalaxyRowPlanetName($galaxyPosition, $currentPlanet, $currentPlayer, $Galaxy, $System, $Planet, 1);
        $output .= GalaxyRowMoon($galaxyPosition, $currentMoon  , $currentPlayer, $Galaxy, $System, $Planet, 3);
        $output .= GalaxyRowDebris($galaxyPosition, $currentPlanet, $currentPlayer, $Galaxy, $System, $Planet, 2);
        $output .= GalaxyRowUser($currentPlanet, $currentPlayer);
        $output .= GalaxyRowAlly($galaxyPosition, $currentPlanet, $currentPlayer, $Galaxy, $System, $Planet, 0);
        $output .= GalaxyRowActions($galaxyPosition, $currentPlanet, $currentPlayer, $Galaxy, $System, $Planet, 0);
        $output .= "</tr>";
    }

    return $output;
}

?>