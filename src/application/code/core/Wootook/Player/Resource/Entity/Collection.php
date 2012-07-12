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

class Wootook_Player_Resource_Entity_Collection
    extends Wootook_Core_Resource_EntityCollection
{
    public function _construct()
    {
        $this->_init('users', 'Wootook_Player_Model_Entity');
    }

    public function addAuthlevelToFilter(Array $levels, $exclude = false)
    {
        if ($exclude === true) {
            $this->addFieldToFilter('authlevel', array(array('nin' => $levels)));
        } else {
            $this->addFieldToFilter('authlevel', array(array('in' => $levels)));
        }

        return $this;
    }

    public function addIsOnlineToFilter($onlineTime = 900)
    {
        $onlineTime = (int) $onlineTime;

        $date = new Wootook_Core_DateTime();
        $date->sub($onlineTime, Wootook_Core_DateTime::TIMESTAMP);

        if ($onlineTime > 0) {
            $this->addFieldToFilter('onlinetime', array(array(
                Wootook_Core_Database_Sql_Select::OPERATOR_DATE => array('from' => $date)
                )));
        }

        return $this;
    }
}
