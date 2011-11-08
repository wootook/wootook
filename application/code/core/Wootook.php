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
     * @var array
     */
    protected static $_config = null;

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
        $availableLocales = self::getConfig('global/locales');

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

    private static function _loadConfig()
    {
        if (self::$_config === null) {
            self::$_config = include ROOT_PATH . DIRECTORY_SEPARATOR . 'config.php';

            if (!is_array(self::$_config)) {
                self::$_config = array();
            }
        }
    }

    public static function getConfig($path = null)
    {
        self::_loadConfig();

        if ($path === null || !is_string($path)) {
            return self::$_config;
        }
        $config = self::$_config;
        foreach (explode('/', $path) as $chunk) {
            if (!isset($config[$chunk])) {
                return null;
            }
            $config = $config[$chunk];
        }
        return $config;
    }

    public static function setConfig($path = null, $value)
    {
        self::_loadConfig();

        if ($path === null || !is_string($path)) {
            return self::$_config;
        }
        $config = &self::$_config;
        foreach (explode('/', $path) as $chunk) {
            if (!isset($config[$chunk])) {
                $config[$chunk] = array();
            }
            $config = &$config[$chunk];
        }
        $config = $value;

        return true;
    }

    public static function getBaseUrl()
    {
        return self::getConfig('global/web/base_url');
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