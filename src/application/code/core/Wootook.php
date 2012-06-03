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
    private static $_request = null;

    /**
     * HTTP response management object
     *
     * @var Legacies_Core_Controller_Response
     */
    private static $_response = null;

    /**
     * The current timestamp
     *
     * @var int
     */
    private static $_now = null;

    /**
     * Default locale identifier
     *
     * @var string
     */
    private static $_defaultLocale = 'fr_FR';

    /**
     *
     * Enter description here ...
     * @var Wootook_Core_Helper_Config_ConfigHandler
     */
    private static $_config = null;

    /**
     *
     * Enter description here ...
     * @var Wootook_Core_Helper_Config_ConfigHandler
     */
    private static $_globalConfig = null;

    /**
     *
     * Enter description here ...
     * @var array
     */
    private static $_websiteConfigs = array();

    /**
     *
     * Enter description here ...
     * @var array
     */
    private static $_gameConfigs = array();

    /**
     *
     * Enter description here ...
     * @var array
     */
    private static $_ignoreDatabaseConfig = false;

    private static $_defaultWebsite = null;
    private static $_defaultGame = null;

    private static $_websitesById = array();
    private static $_websitesByCode = array();
    private static $_gamesById = array();
    private static $_gamesByCode = array();

    public static $isInstalled = true;

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
     * @return Wootook_Core_Model_Session
     */
    public static function getSession($namespace)
    {
        return Wootook_Core_Model_Session::factory($namespace);
    }

    /**
     * @static
     * @param string|null $locale
     * @return Wootook_Core_Model_Translator
     */
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
        $availableLocales = self::getWebsiteConfig('locales');
        if ($availableLocales !== null) {
            return self::getPreferredLocale($availableLocales->toArray());
        }
        return self::getPreferredLocale();
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

        $userLocale = Wootook_Player_Model_Session::getSingleton()->getData('locale');
        if ($userLocale !== null && in_array($userLocale, $availableLocales)) {
            return $userLocale;
        }

        $locales = array();

        if (($accept = self::getRequest()->getServer('HTTP_ACCEPT_LANGUAGE')) !== null) {
            // break up string into pieces (languages and q factors)
            preg_match_all('/([a-z]{1,8}(?:-([a-z]{1,8}))?)\s*(?:;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $accept, $matches);

            $length = count($matches[1]);
            for ($i = 0; $i < $length; $i++) {
                $locale = $matches[1][$i];
                if (!empty($matches[2][$i])) {
                    $locale .= '_' . strtoupper($matches[2][$i]);
                }
                $locales[$locale] = !empty($matches[3][$i]) ? min(max(floatval($matches[3][$i]), 0), 1) : 1;
            }

            arsort($locales, SORT_NUMERIC);
        }

        if (empty($locales)) {
            return self::getDefaultLocale();
        }

        $preferredLocale = current($availableLocales);
        $preferredPriority = 0;
        foreach ($locales as $locale => $piority) {
            if (!in_array($locale, $availableLocales)) {
                continue;
            }
            if ($preferredPriority < $piority) {
                $preferredPriority = $piority;
                $preferredLocale = $locale;
            }
        }

        Wootook_Player_Model_Session::getSingleton()->setData('locale', $preferredLocale);

        return $preferredLocale;
    }

    /**
     * @static
     * @return Wootook_Core_DateTime
     */
    public static function now()
    {
        if (self::$_now === null) {
            self::$_now = time();
        }
        return new Wootook_Core_DateTime(self::$_now);
    }

    /**
     * @return Wootook_Core_Mvc_Controller_Request_Http
     */
    public static function getRequest()
    {
        if (self::$_request === null) {
            self::$_request = new Wootook_Core_Mvc_Controller_Request_Http();
        }
        return self::$_request;
    }

    public static function setRequest($request)
    {
        self::$_request = $request;
    }

    /**
     * @return Wootook_Core_Mvc_Controller_Response_Http
     */
    public static function getResponse()
    {
        if (self::$_response === null) {
            self::$_response = new Wootook_Core_Mvc_Controller_Response_Http();
        }
        return self::$_response;
    }

    public static function setResponse($response)
    {
        self::$_response = $response;
    }

    public static function loadConfig($filename = null)
    {
        self::$_websiteConfigs = array();
        self::$_gameConfigs = array();

        self::$_config = new Wootook_Core_Config_Adapter_Array();
        try {
            self::$_config->load(APPLICATION_PATH . 'configs' . DIRECTORY_SEPARATOR . 'system.php');

            if ($filename === null) {
                $filename = APPLICATION_PATH . 'configs' . DIRECTORY_SEPARATOR . 'local.php';
            }

            if (is_string($filename)) {
                if (!self::$isInstalled) {
                    throw new Wootook_Core_Exception_DataAccessException();
                }
                $localConfig = new Wootook_Core_Config_Adapter_Array($filename);
                self::$_config->merge($localConfig);
            } else if (is_array($filename)) {
                $localConfig = new Wootook_Core_Config_Node($filename);
                self::$_config->merge($localConfig);
            } else if ($filename instanceof Wootook_Core_Config_Node) {
                self::$_config->merge($filename);
            } else {
                throw new Wootook_Core_Exception_DataAccessException();
            }
        } catch (Wootook_Core_Exception_DataAccessException $e) {
            self::$_globalConfig = new Wootook_Core_Config_Node(array(), self::$_config);
            self::$_ignoreDatabaseConfig = true;
            return self::$_config;
        }

        self::$_globalConfig = clone self::$_config['default'];
        if (isset(self::$_config['global'])) {
            self::$_globalConfig->merge(self::$_config['global']);
        }

        self::_appendDatabaseConfig(self::$_globalConfig);

        return self::$_config;
    }

    private static function _appendDatabaseConfig(Wootook_Core_Config_Node $config, $type = 'global', $model = null)
    {
        if (self::$_ignoreDatabaseConfig || !self::$isInstalled) {
            return $config;
        }

        try {
            $adapter = Wootook_Core_Database_ConnectionManager::getSingleton()
                ->getConnection('core_read');
        } catch (Wootook_Core_Exception_Database_AdapterError $e) {
            // This may also occur when the pdo_sqlite driver isn't loaded
            self::$isInstalled = false;
            Wootook_Core_ErrorProfiler::getSingleton()->addException($e);
            return $config;
        }

        $select = $adapter->select($adapter->getTable('core_config'));

        switch ($type) {
        case 'website':
            $select->where(new Wootook_Core_Database_Sql_Placeholder_Expression('website_id = :website_id', array('website_id' => $model->getId())));
            break;

        case 'game':
            $select->where(new Wootook_Core_Database_Sql_Placeholder_Expression('game_id = :game_id', array('game_id' => $model->getId())));
            break;

        default:
            $select->where('website_id', 0);
            $select->where('game_id', 0);
            break;
        }

        $statement = $select->prepare();
        $statement->execute();

        foreach ($statement as $row) {
            $config->setConfig($row['config_path'], $row['config_value']);
        }

        return $config;
    }

    public static function getWebsite($websiteId)
    {
        if (is_numeric($websiteId)) {
            if (isset(self::$_websitesById[$websiteId])) {
                return self::$_websitesById[$websiteId];
            }
            $website = new Wootook_Core_Model_Website();
            try {
                $website->load($websiteId);
                $websiteCode = $website->getData('code');

                self::$_websitesById[$websiteId] = $website;
                self::$_websitesByCode[$websiteCode] = $website;
            } catch (Wootook_Core_Exception_DataAccessException $e) {
                throw new Wootook_Core_Exception_WebsiteError('Could not load website entity.', null, $e);
            }

            return $website;
        } else {
            if (isset(self::$_websitesByCode[$websiteId])) {
                return self::$_websitesByCode[$websiteId];
            }
            $website = new Wootook_Core_Model_Website();
            try {
                $website->load($websiteId, 'code');
                $websiteId = $website->getId();
                $websiteCode = $website->getData('code');

                self::$_websitesById[$websiteId] = $website;
                self::$_websitesByCode[$websiteCode] = $website;
            } catch (Wootook_Core_Exception_DataAccessException $e) {
                throw new Wootook_Core_Exception_WebsiteError('Could not load website entity.', null, $e);
            }

            return $website;
        }
    }

    public static function getGame($gameId)
    {
        if (is_numeric($gameId)) {
            if (isset(self::$_gamesById[$gameId])) {
                return self::$_gamesById[$gameId];
            }
            $game = new Wootook_Core_Model_Game();
            try {
                $game->load($gameId);
                $gameCode = $game->getData('code');

                self::$_gamesById[$gameId] = $game;
                self::$_gamesByCode[$gameCode] = $game;
            } catch (Wootook_Core_Exception_DataAccessException $e) {
                throw new Wootook_Core_Exception_GameError('Could not load game entity.', null, $e);
            }

            return $game;
        } else {
            if (isset(self::$_gamesByCode[$gameId])) {
                return self::$_gamesByCode[$gameId];
            }
            $game = new Wootook_Core_Model_Game();
            try {
                $game->load($gameId, 'code');
                $gameId = $game->getId();
                $gameCode = $game->getData('code');

                self::$_gamesById[$gameId] = $game;
                self::$_gamesByCode[$gameCode] = $game;
            } catch (Wootook_Core_Exception_DataAccessException $e) {
                throw new Wootook_Core_Exception_GameError('Could not load game entity.', null, $e);
            }

            return $game;
        }
    }

    public static function addWebsite(Wootook_Core_Model_Website $website)
    {
        $websiteId = $website->getId();
        $websiteKey = $website->getData('code');
        self::$_websitesById[$websiteId] = $website;
        self::$_websitesByCode[$websiteKey] = $website;
    }

    public static function addGame(Wootook_Core_Model_Game $game)
    {
        $gameId = $game->getId();
        $gameKey = $game->getData('code');
        self::$_gamesById[$gameId] = $game;
        self::$_gamesByCode[$gameKey] = $game;
    }

    public static function setDefaultWebsite(Wootook_Core_Model_Website $website)
    {
        self::$_defaultWebsite = $website;
    }

    public static function getDefaultWebsite()
    {
        if (self::$_defaultWebsite === null) {
            self::$_defaultWebsite = new Wootook_Core_Model_Website();
            self::$_defaultWebsite->setId(1)->setData('code', Wootook_Core_Model_Website::DEFAULT_CODE);
        }
        return self::$_defaultWebsite;
    }

    public static function getDefaultGame()
    {
        if (self::$_defaultGame === null) {
            self::$_defaultGame = new Wootook_Core_Model_Game();
            self::$_defaultGame->setId(1)->setData('code', Wootook_Core_Model_Game::DEFAULT_CODE);
        }
        return self::$_defaultGame;
    }

    public static function setDefaultGame(Wootook_Core_Model_Game $game)
    {
        self::$_defaultGame = $game;
    }

    private static function _initWebsiteConfig($websiteId)
    {
        if (self::$_config === null) {
            self::loadConfig();
        }
        if (!self::$isInstalled) {
            if (isset(self::$_config['default'])) {
                self::$_websiteConfigs[Wootook_Core_Model_Website::DEFAULT_CODE] = clone self::$_config['default'];
            } else {
                self::$_websiteConfigs[Wootook_Core_Model_Website::DEFAULT_CODE] = new Wootook_Core_Config_Node(array(), self::$_config);
            }
            if (isset(self::$_config['install'])) {
                self::$_websiteConfigs[Wootook_Core_Model_Website::DEFAULT_CODE]->merge(self::$_config['install']);
            }

            return self::$_websiteConfigs[Wootook_Core_Model_Website::DEFAULT_CODE];
        }

        try {
            $website = self::getWebsite($websiteId);
        } catch (Wootook_Core_Exception_WebsiteError $e) {
            self::$isInstalled = false;
            Wootook_Core_ErrorProfiler::getSingleton()->addException($e);

            $website = new Wootook_Core_Model_Website();
            $website->setId(0)->setData('code', 'install');
            self::addWebsite($website);
            self::setDefaultWebsite($website);
        } catch (Wootook_Core_Exception_RuntimeException $e) {
            Wootook_Core_ErrorProfiler::getSingleton()->exceptionManager($e);
            return null;
        }
        $websiteKey = $website->getData('code');
        $websiteId = $website->getId();

        if (!isset(self::$_websiteConfigs[$websiteKey])) {
            self::$_websiteConfigs[$websiteKey] = clone self::$_config['default'];
            if ($websiteId != 0 && isset(self::$_config['frontend'])) {
                self::$_websiteConfigs[$websiteKey]->merge(self::$_config['frontend']);
            } else if ($websiteId == 0 && isset(self::$_config['backend'])) {
                self::$_websiteConfigs[$websiteKey]->merge(self::$_config['backend']);
            }

            $websiteConfig = self::$_config->getConfig("website/{$websiteKey}");
            if ($websiteConfig !== null) {
                self::$_websiteConfigs[$websiteKey]->merge($websiteConfig);
            }

            self::_appendDatabaseConfig(self::$_websiteConfigs[$websiteKey], 'website', $website);
        }

        return self::$_websiteConfigs[$websiteKey];
    }

    private static function _initGameConfig($gameId)
    {
        if (self::$_config === null) {
            self::loadConfig();
        }

        if (!self::$isInstalled) {
            if (isset(self::$_config['default'])) {
                self::$_gameConfigs[Wootook_Core_Model_Game::DEFAULT_CODE] = clone self::$_config['default'];
            } else {
                self::$_gameConfigs[Wootook_Core_Model_Game::DEFAULT_CODE] = new Wootook_Core_Config_Node(array(), self::$_config);
            }
            if (isset(self::$_config['install'])) {
                self::$_gameConfigs[Wootook_Core_Model_Game::DEFAULT_CODE]->merge(self::$_config['install']);
            }

            return self::$_gameConfigs[Wootook_Core_Model_Game::DEFAULT_CODE];
        }

        try {
            $game = self::getGame($gameId);
        } catch (Wootook_Core_Exception_GameError $e) {
            self::$isInstalled = false;
            Wootook_Core_ErrorProfiler::getSingleton()->addException($e);

            $game = new Wootook_Core_Model_Game();
            $game->setId(0)->setData('code', 'install')->setData('website_id', self::getDefaultWebsite()->getId());
            self::addGame($game);
            self::setDefaultGame($game);
        } catch (Wootook_Core_Exception_RuntimeException $e) {
            Wootook_Core_ErrorProfiler::getSingleton()->exceptionManager($e);
            return null;
        }

        $gameKey = $game->getData('code');
        $gameId = $game->getId();
        $websiteId = $game->getData('website_id');

        if (!isset(self::$_gameConfigs[$gameKey])) {
            $websiteConfig =  self::_initWebsiteConfig($websiteId);
            if ($websiteConfig === null) {
                return null;
            }
            self::$_gameConfigs[$gameKey] = clone $websiteConfig;

            $gameConfig = self::$_config->getConfig("game/{$gameKey}");
            if ($gameConfig !== null) {
                self::$_gameConfigs[$gameKey]->merge($gameConfig);
            }

            self::_appendDatabaseConfig(self::$_gameConfigs[$gameKey], 'game', $game);
        }

        return self::$_gameConfigs[$gameKey];
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

    /**
     * @static
     * @param null $path
     * @param null $gameKey
     * @return Wootook_Core_Config_Node
     */
    public static function getWebsiteConfig($path = null, $websiteKey = null)
    {
        if (self::$_config === null) {
            self::loadConfig();
        }

        if ($websiteKey === null) {
            //$websiteKey = Wootook_Core_Model_Website::DEFAULT_CODE;
            $websiteKey = self::getDefaultWebsite()->getData('code');
        }

        $config = self::_initWebsiteConfig($websiteKey);
        if ($config === null) {
            return null;
        }

        if ($path !== null) {
            return $config->getConfig($path);
        }
        return $config;
    }

    /**
     * @static
     * @param null $path
     * @param null $gameKey
     * @return Wootook_Core_Config_Node
     */
    public static function getGameConfig($path = null, $gameKey = null)
    {
        if (self::$_config === null) {
            self::loadConfig();
        }

        if ($gameKey === null) {
            //$gameKey = Wootook_Core_Model_Game::DEFAULT_CODE;
            $gameKey = self::getDefaultGame()->getData('code');
        }

        $config = self::_initGameConfig($gameKey);
        if ($config === null) {
            return null;
        }

        if ($path !== null) {
            return $config->getConfig($path);
        }
        return $config;
    }

    public static function setConfig($path, $value, $websiteId = null, $gameId = null)
    {
        if (self::$_config === null) {
            self::loadConfig();
        }

        if (!self::$_ignoreDatabaseConfig) {
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
        } else if ($websiteId !== null) {
            if ($gameId !== null) {
                self::$_gameConfigs[$gameId]->setConfig($path, $value);
            } else {
                self::$_websiteConfigs[$websiteId]->setConfig($path, $value);
            }
        } else {
            self::$_globalConfig->setConfig($path, $value);
        }

        return true;
    }

    public static function getBaseUrl($domain = 'base')
    {
        $urlConfig = self::getGameConfig('web/url');
        if (!$urlConfig instanceof Wootook_Core_Config_Node) {
            return null;
        }
        if (!is_string($domain) || !isset($urlConfig->$domain)) {
            return $urlConfig->base;
        }
        return $urlConfig->$domain;
    }

    public static function getBasePath($domain = 'base')
    {
        $pathConfig = self::getGameConfig('system/path');
        if (!$pathConfig instanceof Wootook_Core_Config_Node) {
            return null;
        }
        if (!is_string($domain) || !isset($pathConfig->$domain)) {
            return $pathConfig->base;
        }
        return $pathConfig->$domain;
    }

    public static function getUrl($uri, Array $params = array())
    {
        $baseUrl = self::getBaseUrl();

        $queryParams = array();
        if (isset($params['_query'])) {
            $queryParams = $params['_query'];
            unset($params['_query']);
        }

        $serializedParams = array();
        foreach ($params as $paramKey => $paramValue) {
            if ($paramValue) {
                $serializedParams[] = "{$paramKey}/{$paramValue}";
            }
        }

        $serializedQueryParams = array();
        foreach ($queryParams as $paramKey => $paramValue) {
            if ($paramValue) {
                $serializedQueryParams[] = "{$paramKey}={$paramValue}";
            }
        }

        if (count($serializedQueryParams) > 0) {
            return $baseUrl . $uri . '/' . implode('/', $serializedParams) . '?' . implode('&', $serializedQueryParams);
        }
        return $baseUrl . $uri . '/' . implode('/', $serializedParams);
    }

    public static function getStaticUrl($uri, Array $params = array())
    {
        $baseUrl = self::getBaseUrl('static');

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

        Wootook_Core_ErrorProfiler::getSingleton()->sleep();
        if (($fp = @fopen($path, 'r', true)) === false) {
            Wootook_Core_ErrorProfiler::getSingleton()->wakeup();
            return false;
        }
        fclose($fp);
        Wootook_Core_ErrorProfiler::getSingleton()->wakeup();
        return true;
    }
}
