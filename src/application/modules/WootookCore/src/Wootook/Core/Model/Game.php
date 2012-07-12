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

use Wootook\Core,
    Wootook\Core\Mvc\Model,
    Wootook\Core\Mvc\Model\Type;

/**
 *
 * Enter description here ...
 *
 * @uses Wootook\Core\BaseObject
 * @uses Legacies_Empire
 */
class Game
    extends Model\Entity
{
    use Type\Full;

    const DEFAULT_CODE = 'default';

    protected $_website = null;

    protected function _construct(Array $data = array())
    {
        $this->_eventPrefix = 'core.game';
        $this->_eventObject = 'game';

        parent::_construct($data);

        $this->_tableName = 'core_game';
        $this->_idFieldName = 'game_id';
    }

    public function getWebsiteId()
    {
        return $this->getData('website_id');
    }

    public function setWebsiteId($websiteId)
    {
        $this->_website = $this->app()->getWebsite($websiteId);
        return $this->setData('website_id', $websiteId);
    }

    /**
     * @return Website
     */
    public function getWebsite()
    {
        if ($this->_website === null) {
            $this->_website = $this->app()->getWebsite($this->getWebsiteId());
        }

        return $this->_website;
    }

    /**
     * @param Website $website
     * @return Game
     */
    public function setWebsite(Website $website)
    {
        $this->_website = $website;
        $this->setWebsiteId($website->getId());

        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->getData('code');
    }

    /**
     * @param string $code
     * @return Game
     */
    public function setCode($code)
    {
        return $this->setData('code', $code);
    }

    /**
     * @return Core\Config\Node
     */
    public function getConfig($path = null)
    {
        if ($this->_config === null) {
            $this->_config = clone $this->getWebsite()->getConfig();

            $this->app()->appendEnvironmentConfig($this->_config, $this->getCode());
            $this->app()->appendDatabaseConfig($this->_config, Core\App\App::SCOPE_GAME, $this->getId());
        }

        if ($path !== null) {
            return $this->_config->getConfig($path);
        }

        return $this->_config;
    }

    public function getBaseUrl()
    {
        return $this->getConfig('web/url/base');
    }
}
