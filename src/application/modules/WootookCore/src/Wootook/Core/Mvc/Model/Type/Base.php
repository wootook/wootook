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

namespace Wootook\Core\Mvc\Model\Type;

use Wootook\Core,
    Wootook\Core\Database\Resource,
    Wootook\Core\Exception as CoreException;

/**
 * Base model implementation, should be consumed by a Wootook\Core\BaseObject derived class
 */
trait Base
{
    protected $_originalData = array();

    protected $_eventPrefix = null;
    protected $_eventObject = null;

    public function _construct(Array $data = array())
    {
        parent::_construct($data);

        $this->_init();
        $this->_setOriginalData();
    }

    protected function _init()
    {
    }

    protected function _setOriginalData()
    {
        $this->_originalData = $this->_data;
    }

    public function getChangedDatas()
    {
        return array_diff_assoc($this->_data, $this->_originalData);
    }

    public function hasChangedDatas()
    {
        if (count($this->getChangedDatas()) > 0) {
            return true;
        }
        return false;
    }

    public function __set($key, $value)
    {
        return $this->setData($key, $value);
    }

    public function __get($key)
    {
        return $this->getData($key);
    }

    public function __unset($key)
    {
        return $this->unsetData($key);
    }

    public function __isset($key)
    {
        return $this->hasData($key);
    }
}
