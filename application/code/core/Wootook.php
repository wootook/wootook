<?php
/**
 * This file is part of Wootook
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.txt
 * @see http://www.wootook.com/
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
 * Bootstrap class, used to access main and global functionalities
 *
 * @package Wootook
 * @category core
 */
class Wootook
{
    /**
     * Static list of application event listeners
     *
     * @var array
     */
    private static $_listeners = array();

    /**
     * Static list of all locale translators
     *
     * @var array
     */
    private static $_translators = array();

    /**
     * HTTP request management object
     *
     * @var Legacies_Core_Controller_Request_Http
     */
    protected static $_request = null;

    /**
     * HTTP response management object
     *
     * @var Legacies_Core_Controller_Response
     */
    protected static $_response = null;

    /**
     * The current timestamp
     *
     * @var int
     */
    protected static $_now = null;

    /**
     * Default locale identifier
     *
     * @var string
     */
    protected static $_defaultLocale = 'fr_FR';

    /**
     *
     * Enter description here ...
     * @var Wootook_Core_Config_Adapter_Abstract
     */
    protected static $_config = null;

    /**
     *
     * Enter description here ...
     * @var Wootook_Core_Config_Adapter_Abstract
     */
    protected static $_globalConfig = null;

    /**
     *
     * Enter description here ...
     * @var array
     */
    protected static $_websiteConfigs = array();

    /**
     *
     * Enter description here ...
     * @var array
     */
    protected static $_gameConfigs = array();

    /**
     * Registers an event listener to be called later in the application.
     *
     * @param string $event The event identifier
     * @param callback $listener The event callback to be called
     */
    public static function registerListener($event, $listener)
    {
        if (!isset(self::$_listeners[$event])) {
            self::$_listeners[$event] = array();
        }
        self::$_listeners[$event][] = $listener;
    }

    /**
     * Clear all event listeners
     */
    public static function clearAllListeners()
    {
        self::$_listeners = array();
    }

    /**
     * Clear a specific event's listeners
     *
     * @param unknown_type $event
     */
    public static function clearEventListeners($event)
    {
        if (isset(self::$_listeners[$event])) {
            self::$_listeners[$event] = array();
        }
    }

    /**
     * Dispatches an event, calls all callbacks that were previously registered
     *
     * @param string $event The event identifier
     * @param array $params The event params
     */
    public static function dispatchEvent($event, $params)
    {
        $eventObject = new Wootook_Core_Event($params);

        if (!isset(self::$_listeners[$event])) {
            return $eventObject;
        }

        foreach (self::$_listeners[$event] as $listener) {
            call_user_func($listener, $eventObject);
        }

        return $eventObject;
    }

    /**
     *
     * Enter description here ...
     * @param unknown_type $namespace
     * @return Legacies_Core_Model_Session
     */
    public static function getSession($namespace)
    {
        return Wootook_Core_Model_Session::factory($namespace);
    }

    public static function getTranslator($locale = null)
    {
        if ($locale === null) {
            $locale = self::getLocale();
        }

        if (!isset($translator[$locale])) {
            $path = dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'locale';
            $translator[$locale] = new Wootook_Core_Model_Translator($path, $locale);
        }
        return $translator[$locale];
    }

    public static function translate($locale, $message, Array $args)
    {
        return self::getTranslator($locale)->translateArgs($message, $args);
    }

    public static function __($message, $_ = null)
    {
        $args = func_get_args();
        array_shift($args);

        return self::getTranslator(self::getLocale())->translateArgs($message, $args);
    }

    public static function getLocale()
    {
        $availableLocales = self::$_config->getConfig('global/locales');

        return self::getPreferredLocale($availableLocales);
    }

    public static function setDefaultLocale($locale)
    {
        $oldLocale = self::$_defaultLocale;
        self::$_defaultLocale = $locale;

        return $oldLocale;
    }

    public static function getDefaultLocale()
    {
        return self::$_defaultLocale;
    }

    public static function getPreferredLocale($availableLocales = array())
    {
        if (empty($availableLocales)) {
            return self::getDefaultLocale();
        }

        $userLocale = self::getSession('user')->getData('locale');
        if ($userLocale !== null) {
            return $userLocale;
        }

        $locales = array();

        if (($accept = self::getRequest()->getServer('HTTP_ACCEPT_LANGUAGE')) !== null) {
            // break up string into pieces (languages and q factors)
            preg_match_all('/([a-z]{1,8}(?:-([a-z]{1,8}))?)\s*(?:;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $accept, $matches);

            $length = count($matches[1]);
            for ($i = 0; $i < $length; $i++) {
                $locale = $lang_parse[1];
                if (!empty($lang_parse[2])) {
                    $locale .= '_' . strtoupper($lang_parse[2]);
                }
                $locales[$locale] = !empty($lang_parse[3]) ? min(max(floatval($lang_parse[3]), 0), 1) : 1;
            }

            arsort($locales, SORT_NUMERIC);
        }

        if (empty($locales)) {
            return self::getDefaultLocale();
        }

        $preferredLocale = key($locales);
        $preferredPriority = current($locales);
        foreach ($locales as $locale => $piority) {
            if (!in_array($locale, $availableLocales)) {
                continue;
            }
            if ($preferredPriority < $piority) {
                $preferredPriority = $piority;
                $preferredLocale = $locale;
            }
        }

        self::getSession('user')->setData('locale', $preferredLocale);

        return $preferredLocale;
    }

    public static function now()
    {
        if (self::$_now === null) {
            self::$_now = time();
        }
        return self::$_now;
    }

    /**
     * @return Wootook_Core_Controller_Request_Http
     */
    public static function getRequest()
    {
        if (self::$_request === null) {
            self::$_request = new Wootook_Core_Controller_Request_Http();
        }
        return self::$_request;
    }

    public static function setRequest($request)
    {
        self::$_request = $request;
    }

    /**
     * @return Wootook_Core_Controller_Response_Http
     */
    public static function getResponse()
    {
        if (self::$_response === null) {
            self::$_response = new Wootook_Core_Controller_Response_Http();
        }
        return self::$_response;
    }

    public static function setResponse($response)
    {
        self::$_response = $response;
    }

    public static function loadConfig($filename = null)
    {
        if ($filename === null) {
            $filename = ROOT_PATH . DIRECTORY_SEPARATOR . 'config.php';
        }

        self::$_config = new Wootook_Core_Config_Adapter_Array($filename);

        self::$_globalConfig = clone self::$_config['default'];
        self::$_globalConfig->merge(self::$_config['global']);

        self::_appendDatabaseConfig(self::$_globalConfig);

        self::$_websiteConfigs = array();
        self::$_gameConfigs = array();

        return self::$_config;
    }

    protected static function _appendDatabaseConfig(Wootook_Core_Config_Node $config, $type = 'global', $id = 0)
    {
        $collection = new Wootook_Core_Collection('core_config', 'Wootook_Core_Model_Config');

        switch ($type) {
        case 'website':
            $collection->where('website_id = :website_id');
            $collection->load(array('website_id' => $id));
            break;

        case 'game':
            $collection->where('game_id = :game_id');
            $collection->load(array('game_id' => $id));
            break;

        default:
            $collection->where('website_id = 0');
            $collection->where('game_id = 0');
            $collection->load(array());
            break;
        }

        foreach ($collection as $row) {
            $config->setConfig($row->getData('config_path'), $row->getData('config_value'));
        }

        return $config;
    }

    protected static function _initWebsiteConfig($websiteId)
    {
        if (self::$_config === null) {
            self::loadConfig();
        }

        $websiteId = 1;

        if (!isset(self::$_websiteConfigs[$websiteId])) {
            self::$_websiteConfigs[$websiteId] = clone self::$_globalConfig;

            $websiteConfig = self::$_config->getConfig("website/{$websiteId}");
            if ($websiteConfig !== null) {
                self::$_websiteConfigs[$websiteId]->merge($websiteConfig);
            }

            self::_appendDatabaseConfig(self::$_websiteConfigs[$websiteId], 'website', $websiteId);
        }

        return self::$_websiteConfigs[$websiteId];
    }

    protected static function _initGameConfig($gameId)
    {
        if (self::$_config === null) {
            self::loadConfig();
        }

        $websiteId = 1;
        $gameId = 1;

        if (!isset(self::$_gameConfigs[$gameId])) {
            self::_initWebsiteConfig($websiteId);

            self::$_gameConfigs[$gameId] = clone self::$_websiteConfigs[$websiteId];

            $gameConfig = self::$_config->getConfig("game/{$gameId}");
            if ($gameConfig !== null) {
                self::$_gameConfigs[$gameId]->merge($gameConfig);
            }

            self::_appendDatabaseConfig(self::$_gameConfigs[$gameId], 'game', $gameId);
        }

        return self::$_gameConfigs[$gameId];
    }

    public static function getConfig($path = null)
    {
        if (self::$_config === null) {
            self::loadConfig();
        }

        if ($path !== null) {
            return self::$_globalConfig->getConfig($path);
        }
        return self::$_globalConfig;
    }

    public static function getWebsiteConfig($path = null, $websiteId = null)
    {
        if (self::$_config === null) {
            self::loadConfig();
        }

        $websiteId = 1;

        self::_initWebsiteConfig($websiteId);

        if ($path !== null) {
            return self::$_websiteConfigs[$websiteId]->getConfig($path);
        }
        return self::$_websiteConfigs[$websiteId];
    }

    public static function getGameConfig($path = null, $gameId = null)
    {
        if (self::$_config === null) {
            self::loadConfig();
        }

        $gameId = 1;

        self::_initGameConfig($gameId);

        if ($path !== null) {
            return self::$_gameConfigs[$gameId]->getConfig($path);
        }
        return self::$_gameConfigs[$gameId];
    }

    public static function setConfig($path, $value, $websiteId = null, $gameId = null)
    {
        if (self::$_config === null) {
            self::loadConfig();
        }

        $updater = new Wootook_Core_Model_Config();
        $updater->setPath($path)->setValue($value);
        if ($websiteId !== null) {
            $updater->setWebsiteId($websiteId);
            if ($gameId !== null) {
                $updater->setGameId($gameId);
                self::$_gameConfigs[$gameId]->setConfig($path, $value);
            } else {
                self::$_websiteConfigs[$websiteId]->setConfig($path, $value);
            }
        } else {
            self::$_globalConfig->setConfig($path, $value);
        }
        $updater->save();

        return true;
    }

    public static function getBaseUrl()
    {
        return self::$_config->getConfig('global/web/base_url');
    }

    public static function getSkinUrl($package, $theme, $uri, Array $params = array())
    {
        return self::getUrl("skin/{$package}/{$theme}/{$uri}", $params);
    }

    public static function getUrl($uri, Array $params = array())
    {
        $baseUrl = self::getBaseUrl();

        $serializedParams = array();
        foreach ($params as $paramKey => $paramValue) {
            if ($paramValue) {
                $serializedParams[] = "{$paramKey}={$paramValue}";
            }
        }

        if (count($serializedParams) > 0) {
            return $baseUrl . $uri . '?' . implode('&', $serializedParams);
        }
        return $baseUrl . $uri;
    }

    public static function fileExists($path)
    {
        if ($path === null || empty($path)) {
            return false;
        }

        Wootook_Core_ErrorProfiler::sleep();
        if (($fp = @fopen($path, 'r', true)) === false) {
            Wootook_Core_ErrorProfiler::wakeup();
            return false;
        }
        fclose($fp);
        Wootook_Core_ErrorProfiler::wakeup();
        return true;
    }
}