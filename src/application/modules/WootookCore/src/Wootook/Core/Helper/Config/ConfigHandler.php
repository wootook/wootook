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

namespace Wootook\Core\Helper\Config;

use Wootook\Core\Mvc\Model\Type,
    Wootook\Core\Mvc\Model\Action;

abstract class ConfigHandler
    implements \Iterator, \Countable
{
    protected $_data = array();

    protected function _initData($filename)
    {
        $config = \Wootook::app()->getDefaultGame()->getConfig('engine/storyline');

        if ($config === null) {
            $config = array(
                'universe' => 'default',
                'episode'  => 'default'
                );
        } else {
            $config = $config->toArray();
        }

        $path = 'gamedata' . DIRECTORY_SEPARATOR . $config['universe'] . DIRECTORY_SEPARATOR
            . $config['episode'] . DIRECTORY_SEPARATOR . $filename . '.php';

        foreach (include APPLICATION_PATH . $path as $elementId => $fieldName) {
            $this->setData($elementId, $fieldName);
        }

        return $this;
    }

    public function count()
    {
        return count($this->_data);
    }

    public function current()
    {
        return current($this->_data);
    }

    public function next()
    {
        return next($this->_data);
    }

    public function key()
    {
        return key($this->_data);
    }

    public function valid()
    {
        return $this->current() !== false;
    }

    public function rewind()
    {
        reset($this->_data);
    }
}
