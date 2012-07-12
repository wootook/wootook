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

namespace Wootook\Core\Event;

use Wootook\Core,
    Wootook\Core\Exception as CoreException;

/**
 * Event
 */
class Event
    extends Core\Base\BaseObject
{
    protected $_propagationStopped = false;

    public function __call($method, $params)
    {
        $prefix = substr($method, 0, 3);
        $dataKey = substr($method, 3);

        $dataKey = str_replace(' ', '', ucwords(str_replace('-', ' ', $dataKey)));
        $dataKey = str_replace(' ', '_', ucwords(str_replace('.', ' ', $dataKey)));

        switch ($prefix) {
        case 'get':
            return $this->getData($dataKey);
        case 'set':
            return $this->setData($dataKey, isset($params[0]) ? $params[0] : null);
        case 'uns':
            return $this->unsetData($dataKey);
        case 'has':
            return $this->hasData($dataKey);
        }

        throw new CoreException\RuntimeException(sprintf('Method %d does not exist.', $method));
    }

    public function setType($type)
    {
        return $this->setData('type', $type);
    }

    public function getType()
    {
        return $this->getData('type');
    }

    public function hasType()
    {
        return $this->hasData('type');
    }

    public function unsType()
    {
        return $this->unsetData('type');
    }

    public function setTarget($target)
    {
        return $this->setData('target', $target);
    }

    public function getTarget()
    {
        return $this->getData('target');
    }

    public function hasTarget()
    {
        return $this->hasData('target');
    }

    public function unsTarget()
    {
        return $this->unsetData('target');
    }

    public function stopPropagation()
    {
        $this->_propagationStopped = true;

        return $this;
    }

    public function isPropagationStopped()
    {
        return $this->_propagationStopped;
    }
}
