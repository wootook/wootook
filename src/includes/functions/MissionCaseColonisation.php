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
 * @param Wootook_Empire_Model_Fleet|array $fleet
 */
function MissionCaseColonisation($fleet)
{
    $readConnection = Wootook_Core_Database_ConnectionManager::getSingleton()->getConnection('core_read');

    $player = new Wootook_Player_Model_Entity();
    $player->load($fleet->getData('fleet_owner'));
    if (!$player->getId()) {
        $fleet->delete();
        return;
    }

    $event = Wootook::dispatchEvent('fleet.mission.colonize.max-allowed-planets', array(
         'base_count' => MAX_PLAYER_PLANETS,
         'count'      => MAX_PLAYER_PLANETS,
         'player'     => $player,
         'fleet'      => $fleet
         ));

    $maxAllowedPlanetCount = $event->getData('count');

    /* first trip */
    if ($fleet->getData('fleet_mess') == 0) {
        if ($fleet->getActionTime()->isEarlier()) {
            return;
        }
        if ($player->getPlanetCollection()->getSize() >= $maxAllowedPlanetCount && !$player->isAuthorized(array(LEVEL_ADMIN))) {
            /* no more planets to colonize */

            $coords = sprintf('%d:%d:%d', $fleet->getData('fleet_end_galaxy'), $fleet->getData('fleet_end_system'), $fleet->getData('fleet_end_planet'));
            SendSimpleMessage($player->getId(), null, $fleet['fleet_end_time'], 0,
                Wootook::__('Colonization'), Wootook::__('Colonization report'),
                Wootook::__("The fleet has arrived at the coordinates [%1\$s], but unfortunatly colonization cannot happen : you can't have more than %2\$d colonies.", $coords, $maxAllowedPlanetCount));

            $fleet->goBack();
            return;
        }

        $statement = $readConnection->select()
            ->column(new Wootook_Core_Database_Sql_Placeholder_Expression('COUNT(*)'))
            ->from($readConnection->getTable('planets'))
            ->where('galaxy', $fleet->getData('fleet_end_galaxy'))
            ->where('system', $fleet->getData('fleet_end_system'))
            ->where('planet', $fleet->getData('fleet_end_planet'))
            ->prepare()
        ;

        $statement->execute();
        if ($statement->fetchColumn() > 0) {
            $coords = sprintf('%d:%d:%d', $fleet->getData('fleet_end_galaxy'), $fleet->getData('fleet_end_system'), $fleet->getData('fleet_end_planet'));
            SendSimpleMessage($player->getId(), null, $fleet['fleet_end_time'], 0,
                Wootook::__('Colonization'), Wootook::__('Colonization report'),
                Wootook::__("The fleet has arrived at the coordinates [%1\$s], but unfortunatly colonization cannot happen : the planet is already colonized.", $coords));

            $fleet->goBack();
            return;
        }

        if (mt_rand(0, 100) >= 75) {
            $baseSize = Wootook::getGameConfig('planet/initial/fields');
            $factor = 2 * $position / (1 + log($position));
            $fuzz = 2 * $factor * pow(sin($factor), 2) / 2 + $factor / 4;
            $size = ($baseSize * mt_rand(floor($factor / 10), ceil($factor * 5 / 4))) + mt_rand(0, $fuzz);

            $player->createNewPlanet(
                $fleet->getData('fleet_end_galaxy'),
                $fleet->getData('fleet_end_system'),
                $fleet->getData('fleet_end_planet'),
                Wootook_Empire_Model_Planet::TYPE_PLANET,
                Wootook::__('Colony'),
                $size
            );

            $coords = sprintf('%d:%d:%d', $fleet->getData('fleet_end_galaxy'), $fleet->getData('fleet_end_system'), $fleet->getData('fleet_end_planet'));
            SendSimpleMessage($player->getId(), null, $fleet['fleet_end_time'], 0,
                Wootook::__('Colonization'), Wootook::__('Colonization report'),
                Wootook::__("The fleet has arrived at the coordinates [%1\$s], the settlers succeeded creating your new colony.", $coords));

            $fleet->delete();
            return;
        } else {
            $coords = sprintf('%d:%d:%d', $fleet->getData('fleet_end_galaxy'), $fleet->getData('fleet_end_system'), $fleet->getData('fleet_end_planet'));
            SendSimpleMessage($player->getId(), null, $fleet['fleet_end_time'], 0,
                Wootook::__('Colonization'), Wootook::__('Colonization report'),
                Wootook::__("The fleet has arrived at the coordinates [%1\$s], the settlers failed creating your new colony, no planet was there.", $coords));

            $fleet->goBack();
            return;
        }
    }

    /* back trip */
    if ($fleet->getArrivalTime()->isEarlier()) {
        return;
    }

    $fleet->dock($fleet->getOriginPlanet());

    $coords = sprintf('%d:%d:%d', $fleet->getData('fleet_end_galaxy'), $fleet->getData('fleet_end_system'), $fleet->getData('fleet_end_planet'));
    SendSimpleMessage($player->getId(), null, $fleet['fleet_end_time'], 0,
        Wootook::__('Colonization'), Wootook::__('Colonization report'),
        Wootook::__("The fleet went back from the coordinates [%1\$s], the settlers failed creating your new colony.", $coords));
    return;
}