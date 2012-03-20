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
class Wootook_Object
    implements ArrayAccess
{
    protected $_data = array();

    public function __construct(Array $data = array())
    {
        $this->_data = $data;
    }

    public function getData($key)
    {
        if ($this->hasData($key)) {
            return $this->_data[$key];
        }
        return null;
    }

    public function getAllDatas()
    {
        return $this->_data;
    }

    public function hasData($key)
    {
        return (bool) isset($this->_data[$key]);
    }

    public function setData($key, $value)
    {
        $this->_data[$key] = $value;

        return $this;
    }

    public function addData(Array $data)
    {
        foreach ($data as $key => $value) {
            $this->setData($key, $value);
        }

        return $this;
    }

    public function unsetData($key)
    {
        if ($this->hasData($key)) {
            unset($this->_data[$key]);
        }

        return $this;
    }

    public function clearData()
    {
        $this->_data = array();

        return $this;
    }

    public function offsetExists($offset)
    {
        return $this->hasData($offset);
    }

    public function offsetGet($offset)
    {
        return $this->getData($offset);
    }

    public function offsetSet($offset, $data)
    {
        return $this->setData($offset, $data);
    }

    public function offsetUnset($offset)
    {
        return $this->unsetData($offset);
    }
}
