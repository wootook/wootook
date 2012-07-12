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

namespace Wootook\Core\Model;

use Wootook\Core\Mvc\Model;

/**
 *
 * Enter description here ...
 *
 * @uses Wootook\Core\BaseObject
 * @uses Legacies_Empire
 */
class Config
    extends Model\SubTable
{
    protected $_eventPrefix = 'core.config';
    protected $_eventObject = 'config';

    protected function _init()
    {
        $this->_tableName = 'core_config';
        $this->_idFieldNames = array('config_path', 'website_id', 'game_id');
    }

    public function setWebsiteId($websiteId)
    {
        return $this->setData('website_id', $websiteId);
    }

    public function getWebsiteId()
    {
        return $this->getData('website_id');
    }

    public function setGameId($gameId)
    {
        return $this->setData('game_id', $gameId);
    }

    public function getGameId()
    {
        return $this->getData('game_id');
    }

    public function setPath($path)
    {
        return $this->setData('config_path', $path);
    }

    public function getPath()
    {
        return $this->getData('config_path');
    }

    public function setValue($value)
    {
        return $this->setData('config_value', $value);
    }

    public function getValue()
    {
        return $this->getData('config_value');
    }
}
