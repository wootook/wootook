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
    Wootook\Core\Base;

/**
 *
 * Enter description here ...
 *
 * @uses Wootook\Core\BaseObject
 * @uses Legacies_Empire
 */
class Session
{
    use Base\Service\App, Base\DataContainer;

    const CRIT    = 0x80;
    const ERR     = 0x40;
    const WARN    = 0x20;
    const INFO    = 0x10;
    const DEBUG   = 0x01;
    const SUCCESS = 0;

    protected static $_instances = null;

    protected static $_levels = array(
        self::CRIT    => 'CRIT',
        self::ERR     => 'ERR',
        self::WARN    => 'WARN',
        self::INFO    => 'INFO',
        self::DEBUG   => 'DEBUG',
        self::SUCCESS => 'SUCCESS'
        );

    protected $_messages = array();

    const COOKIE_LIFETIME_CONFIG_KEY = 'web/session/time';
    const COOKIE_DOMAIN_CONFIG_KEY   = 'web/session/domain';
    const COOKIE_PATH_CONFIG_KEY     = 'web/session/path';

    const IDENTIFIER_MESSAGES = 'messages';
    const IDENTIFIER_DATA     = 'data';

    public static function factory($namespace)
    {
        $namespace = (string) $namespace;
        if (!isset(self::$_instances[$namespace])) {
            self::$_instances[$namespace] = new self($namespace);
        }
        return self::$_instances[$namespace];
    }

    public static function destroy()
    {
        session_destroy();
    }

    public function _construct($namespace)
    {
        if (session_id() == '') {
            session_set_cookie_params($this->getCookieLifetime(), $this->getCookiePath(), $this->getCookieDomain(), false, true);

            session_cache_limiter('private_no_expire');
            session_cache_expire(15);

            session_start();
            session_regenerate_id(true);
        }

        if (!isset($_SESSION[$namespace])) {
            $_SESSION[$namespace] = array(
                self::IDENTIFIER_MESSAGES => array(),
                self::IDENTIFIER_DATA     => array()
                );
        }

        if (!isset($_SESSION[$namespace][self::IDENTIFIER_MESSAGES]) || !is_array($_SESSION[$namespace][self::IDENTIFIER_MESSAGES])) {
            $_SESSION[$namespace][self::IDENTIFIER_MESSAGES] = array();
        }

        $this->_data = &$_SESSION[$namespace][self::IDENTIFIER_DATA];
        $this->_messages = &$_SESSION[$namespace][self::IDENTIFIER_MESSAGES];
    }

    public function getCookieLifetime()
    {
        $lifetime = \Wootook::app()->getDefaultWebsite()->getConfig(self::COOKIE_LIFETIME_CONFIG_KEY);
        if (!is_numeric($lifetime) || $lifetime <= 0) {
            return 900;
        }

        return $lifetime;
    }

    public function getCookieDomain()
    {
        $domain = \Wootook::app()->getDefaultWebsite()->getConfig(self::COOKIE_DOMAIN_CONFIG_KEY);
        if ($domain === null) {
            return $_SERVER['SERVER_NAME'];
        }
        return $domain;
    }

    public function getCookiePath()
    {
        $path = \Wootook::app()->getDefaultWebsite()->getConfig(self::COOKIE_PATH_CONFIG_KEY);
        if ($path === null) {
            return '/';
        }
        return $path;
    }

    public function getMessages($clear = true)
    {
        $messages = $this->_messages;
        if ($clear == true) {
            $this->_messages = array();
        }
        return $messages;
    }

    public function addMessage($message, $type = self::DEBUG)
    {
        if (!isset(self::$_levels[$type])) {
            $type = self::DEBUG;
        }

        if (!isset($this->_messages)) {
            $this->_messages = array();
        }
        if (!isset($this->_messages[self::$_levels[$type]])) {
            $this->_messages[self::$_levels[$type]] = array();
        }
        $this->_messages[self::$_levels[$type]][] = $message;

        return $this;
    }

    public function addCritical($message, $_ = null)
    {
        $args = func_get_args();
        array_shift($args);

        return $this->addMessage(vsprintf($message, $args), self::CRIT);
    }

    public function addError($message, $_ = null)
    {
        $args = func_get_args();
        array_shift($args);

        return $this->addMessage(vsprintf($message, $args), self::ERR);
    }

    public function addWarning($message, $_ = null)
    {
        $args = func_get_args();
        array_shift($args);

        return $this->addMessage(vsprintf($message, $args), self::WARN);
    }

    public function addSuccess($message, $_ = null)
    {
        $args = func_get_args();
        array_shift($args);

        return $this->addMessage(vsprintf($message, $args), self::SUCCESS);
    }

    public function addInfo($message, $_ = null)
    {
        $args = func_get_args();
        array_shift($args);

        return $this->addMessage(vsprintf($message, $args), self::INFO);
    }

    public function addDebug($message, $_ = null)
    {
        $args = func_get_args();
        array_shift($args);

        return $this->addMessage(vsprintf($message, $args), self::DEBUG);
    }

    public function initFormKey()
    {
        $key = $this->getData('form_key');
        $this->setData('form_key', uniqid());

        return $key;
    }

    public function getFormKey($reset = true)
    {
        if (!$this->getData('form_key')) {
            $this->initFormKey();
            return $this->getData('form_key');
        }

        if ($reset === true) {
            $this->initFormKey();
        }

        return $this->getData('form_key');
    }

    public function setFormData(Array $data = array())
    {
        if (isset($data['__formkey'])) {
            unset($data['__formkey']);
        }

        $this->setData('form_data', $data);

        return $this;
    }

    public function getFormData($key = null, $default = null)
    {
        $data = $this->getData('form_data');
        if ($key === null) {
            return $data;
        } else if (isset($data[$key])) {
            return $data[$key];
        }
        return $default;
    }
}
