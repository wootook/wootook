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

use Wootook\Core\Exception as CoreException,
    Wootook\Core\Profiler;

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
     * @var array
     */
    private static $_ignoreDatabaseConfig = false;

    public static $isInstalled = true;

    protected static $_app = array();

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
        $eventObject = new Wootook\Core\Event\Event(self::app(), $params);

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
     * @return Wootook\Core\Model\Session
     */
    public static function getSession($namespace)
    {
        return Wootook\Core\Model\Session::factory($namespace);
    }

    /**
     * @static
     * @param string|null $locale
     * @return Wootook\Core\Model\Translator
     */
    public static function getTranslator($locale = null)
    {
        if ($locale === null) {
            $locale = self::getLocale();
        }

        if (!isset($translator[$locale])) {
            $path = dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'locale';
            $translator[$locale] = new Wootook\Core\Model\Translator($path, $locale);
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
        $availableLocales = self::app()->getDefaultWebsite()->getConfig('locales');
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

        // FIXME: Dependency should not exist
        /*
        $userLocale = Wootook\Player\Model\Session::getSingleton()->getData('locale');
        if ($userLocale !== null && in_array($userLocale, $availableLocales)) {
            return $userLocale;
        }
        */

        $locales = array();

        if (($accept = self::app()->getFrontController()->getRequest()->getServer('HTTP_ACCEPT_LANGUAGE')) !== null) {
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

        // FIXME: Dependency should not exist
        //Wootook\Player\Model\Session::getSingleton()->setData('locale', $preferredLocale);

        return $preferredLocale;
    }

    /**
     * @static
     * @return Wootook\Core\DateTime
     */
    public static function now()
    {
        if (self::$_now === null) {
            self::$_now = time();
        }
        return new Wootook\Core\DateTime(self::$_now);
    }

    /**
     * @static
     * @return Wootook\Core\App
     */
    public static function app($domain = Wootook\Core\App::DOMAIN_FRONTEND, $environment = Wootook\Core\App::ENV_PRODUCTION)
    {
        if (!isset(self::$_app[$environment])) {
            self::$_app[$environment] = array();
        }
        if (!isset(self::$_app[$environment][$domain])) {
            $configPath = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'configs';
            self::$_app[$environment][$domain] = new Wootook\Core\App($configPath, $domain, $environment);
        }

        return self::$_app[$environment][$domain];
    }

    /**
     * @deprecated
     * @return Wootook\Core\Mvc\Controller\Request\Request
     */
    public static function getRequest()
    {
        Profiler\ErrorProfiler::getSingleton()
            ->addException(new CoreException\RuntimeException('Method ' . __METHOD__ . ' is deprecated.'));
        return self::app()->getFrontController()->getRequest();
    }

    /**
     * @static
     * @deprecated
     * @param Wootook\Core\Mvc\Controller\Request\Request $request
     */
    public static function setRequest(Wootook\Core\Mvc\Controller\Request\Request $request)
    {
        Profiler\ErrorProfiler::getSingleton()
            ->addException(new CoreException\RuntimeException('Method ' . __METHOD__ . ' is deprecated.'));
        self::app()->getFrontController()->setRequest($request);
    }

    /**
     * @deprecated
     * @return Wootook\Core\Mvc\Controller\Response\Response
     */
    public static function getResponse()
    {
        Profiler\ErrorProfiler::getSingleton()
            ->addException(new CoreException\RuntimeException('Method ' . __METHOD__ . ' is deprecated.'));
        return self::app()->getFrontController()->getResponse();
    }

    /**
     * @static
     * @deprecated
     * @param Wootook\Core\Mvc\Controller\Response\Response $response
     */
    public static function setResponse(Wootook\Core\Mvc\Controller\Response\Response $response)
    {
        Profiler\ErrorProfiler::getSingleton()
            ->addException(new CoreException\RuntimeException('Method ' . __METHOD__ . ' is deprecated.'));
        self::app()->getFrontController()->setResponse($response);
    }

    /**
     * @static
     * @deprecated
     * @param null $filename
     * @return null|Wootook_Core_Helper_Config_ConfigHandler
     * @throws CoreException\DataAccessException
     */
    public static function loadConfig($filename = null)
    {
        Profiler\ErrorProfiler::getSingleton()
            ->addException(new CoreException\RuntimeException('Method ' . __METHOD__ . ' is deprecated.'));
        return self::app()->getGlobalConfig();
    }

    /**
     * @static
     * @deprecated
     * @param $websiteId
     * @return mixed
     */
    public static function getWebsite($websiteId)
    {
        Profiler\ErrorProfiler::getSingleton()
            ->addException(new CoreException\RuntimeException('Method ' . __METHOD__ . ' is deprecated.'));
        return self::app()->getWebsite($websiteId);
    }

    /**
     * @static
     * @deprecated
     * @param $gameId
     * @return Wootook_Core_Model_Game
     * @throws CoreException\GameError
     */
    public static function getGame($gameId)
    {
        Profiler\ErrorProfiler::getSingleton()
            ->addException(new CoreException\RuntimeException('Method ' . __METHOD__ . ' is deprecated.'));
        return self::app()->getGame($gameId);
    }

    /**
     * @static
     * @deprecated
     * @param Wootook_Core_Model_Website $website
     */
    public static function addWebsite(Wootook_Core_Model_Website $website)
    {
        Profiler\ErrorProfiler::getSingleton()
            ->addException(new CoreException\RuntimeException('Method ' . __METHOD__ . ' is deprecated.'));
    }

    /**
     * @static
     * @deprecated
     * @param Wootook_Core_Model_Game $game
     */
    public static function addGame(Wootook_Core_Model_Game $game)
    {
        Profiler\ErrorProfiler::getSingleton()
            ->addException(new CoreException\RuntimeException('Method ' . __METHOD__ . ' is deprecated.'));
    }

    /**
     * @static
     * @deprecated
     * @param Wootook_Core_Model_Website $website
     */
    public static function setDefaultWebsite(Wootook_Core_Model_Website $website)
    {
        Profiler\ErrorProfiler::getSingleton()
            ->addException(new CoreException\RuntimeException('Method ' . __METHOD__ . ' is deprecated.'));
        self::app()->setDefaultWebsiteId($website->getId());
    }

    /**
     * @static
     * @deprecated
     * @param Wootook_Core_Model_Game $game
     */
    public static function setDefaultGame(Wootook_Core_Model_Game $game)
    {
        Profiler\ErrorProfiler::getSingleton()
            ->addException(new CoreException\RuntimeException('Method ' . __METHOD__ . ' is deprecated.'));
        self::app()->setDefaultGameId($game->getId());
    }

    /**
     * @static
     * @deprecated
     * @return mixed
     */
    public static function getDefaultWebsite()
    {
        Profiler\ErrorProfiler::getSingleton()
            ->addException(new CoreException\RuntimeException('Method ' . __METHOD__ . ' is deprecated.'));
        return self::app()->getDefaultWebsite();
    }

    /**
     * @static
     * @deprecated
     * @return mixed
     */
    public static function getDefaultGame()
    {
        Profiler\ErrorProfiler::getSingleton()
            ->addException(new CoreException\RuntimeException('Method ' . __METHOD__ . ' is deprecated.'));
        return self::app()->getDefaultGame();
    }

    /**
     * @static
     * @deprecated
     * @param null $path
     * @return null|Wootook_Core_Config_Adapter_Array
     */
    public static function getConfig($path = null)
    {
        Profiler\ErrorProfiler::getSingleton()
            ->addException(new CoreException\RuntimeException('Method ' . __METHOD__ . ' is deprecated.'));
        return self::app()->getGlobalConfig($path);
    }

    /**
     * @static
     * @deprecated
     * @param null $path
     * @param null $gameKey
     * @return Wootook_Core_Config_Node
     */
    public static function getWebsiteConfig($path = null, $websiteKey = null)
    {
        Profiler\ErrorProfiler::getSingleton()
            ->addException(new CoreException\RuntimeException('Method ' . __METHOD__ . ' is deprecated.'));

        if ($websiteKey === null) {
            $websiteKey = self::app()->getDefaultWebsiteId();
        }

        return self::app()->getWebsite($websiteKey)->getConfig($path);
    }

    /**
     * @static
     * @deprecated
     * @param null $path
     * @param null $gameKey
     * @return Wootook_Core_Config_Node
     */
    public static function getGameConfig($path = null, $gameKey = null)
    {
        Profiler\ErrorProfiler::getSingleton()
            ->addException(new CoreException\RuntimeException('Method ' . __METHOD__ . ' is deprecated.'));

        if ($gameKey === null) {
            $gameKey = self::app()->getDefaultGameId();
        }

        return self::app()->getGame($gameKey)->getConfig($path);
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
        $urlConfig = self::app()->getDefaultGame()->getConfig('web/url');
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
        $pathConfig = self::app()->getDefaultGame()->getConfig('system/path');
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

        Profiler\ErrorProfiler::getSingleton()->sleep();
        if (($fp = @fopen($path, 'r', true)) === false) {
            Profiler\ErrorProfiler::getSingleton()->wakeup();
            return false;
        }
        fclose($fp);
        Profiler\ErrorProfiler::getSingleton()->wakeup();
        return true;
    }
}
