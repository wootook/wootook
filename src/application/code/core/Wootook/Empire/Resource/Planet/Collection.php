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

class Wootook_Empire_Resource_Planet_Collection
    extends Wootook_Core_Resource_EntityCollection
{
    protected function _construct()
    {
        $this->_init('planets', 'Wootook_Empire_Model_Planet');
    }

    public function addPlayerToFilter(Wootook_Player_Model_Entity $player)
    {
        $this->addFieldToFilter('id_owner', $player->getId());

        return $this;
    }

    public function addGalaxyToFilter($galaxy)
    {
        $this->addFieldToFilter('galaxy', $galaxy);

        return $this;
    }

    public function addSystemToFilter($system)
    {
        $this->addFieldToFilter('system', $system);

        return $this;
    }

    public function addPositionToFilter($position)
    {
        $this->addFieldToFilter('planet', $position);

        return $this;
    }

    public function addTypeToFilter($type)
    {
        $this->addFieldToFilter('planet_type', $type);

        return $this;
    }

    public function addCoordsToFilter($galaxy, $system = null, $position = null, $type = null)
    {
        $this->addGalaxyToFilter($galaxy);

        if ($system !== null) {
            $this->addSystemToFilter($system);

            if ($position !== null) {
                $this->addPositionToFilter($position);

                if ($type !== null) {
                    $this->addTypeToFilter($type);
                }
            }
        }

        return $this;
    }
}
