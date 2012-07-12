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

class Wootook_Empire_Resource_Fleet_Collection
    extends Wootook_Core_Resource_EntityCollection
{
    protected function _construct()
    {
        $this->_init('fleets', 'Wootook_Empire_Model_Fleet');
    }

    public function addPlanetToFilter(Wootook_Empire_Model_Planet $planet, $time = null)
    {
        if ($time === null) {
            $time = new Wootook_Core_DateTime();
        }
        $this->addFieldToFilter(null, array(
            array('and' => array(
                array('eq'   => array('field' => 'fleet_start_galaxy', 'value' => $planet->getGalaxy())),
                array('eq'   => array('field' => 'fleet_start_system', 'value' => $planet->getSystem())),
                array('eq'   => array('field' => 'fleet_start_planet', 'value' => $planet->getPosition())),
                array('eq'   => array('field' => 'fleet_start_type', 'value' => $planet->getType())),
                //array('date' => array('field' => 'fleet_start_time', 'value' => array('to' => $time))),
                )),
            array('and' => array(
                array('eq'   => array('field' => 'fleet_end_galaxy', 'value' => $planet->getGalaxy())),
                array('eq'   => array('field' => 'fleet_end_system', 'value' => $planet->getSystem())),
                array('eq'   => array('field' => 'fleet_end_planet', 'value' => $planet->getPosition())),
                array('eq'   => array('field' => 'fleet_end_type', 'value' => $planet->getType())),
                //array('date' => array('field' => 'fleet_end_time', 'value' => array('to' => $time))),
                ))
            ));

        return $this;
    }

    public function addIsVisibleToFilter(Wootook_Player_Model_Entity $player, $time = null)
    {
        if ($time === null) {
            $time = new Wootook_Core_DateTime();
        }
        $this->addFieldToFilter(null, array(
            array('and' => array(
                array('eq'   => array('field' => 'fleet_owner', 'value' => $player->getId())),
                array('date' => array('field' => 'fleet_start_time', 'value' => array('to' => $time))),
                )),
            array('and' => array(
                array('eq'   => array('field' => 'fleet_owner', 'value' => $player->getId())),
                array('date' => array('field' => 'fleet_end_time', 'value' => array('to' => $time))),
                ))
            ));

        return $this;
    }
}
