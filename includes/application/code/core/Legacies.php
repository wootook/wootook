<?php
/**
 * This file is part of XNova:Legacies
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://www.xnova-ng.org/
 *
 * Copyright (c) 2009-Present, XNova Support Team <http://www.xnova-ng.org>
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing XNova.
 *
 */

/**
 * Bootstrap class, used to access main and global functionalities
 *
 * @package Legacies
 * @category core
 */
class Legacies
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
        $eventObject = new Legacies_Core_Event($params);

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
        return Legacies_Core_Model_Session::factory($namespace);
    }

    public static function getTranslator($locale = null)
    {
        if ($locale === null) {
            $locale = self::getDefaultLocale();
        }

        if (!isset($translator[$locale])) {
            $path = dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'locale';
            $translator[$locale] = new Legacies_Core_Model_Translator($path, $locale);
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

        return self::getTranslator(self::getDefaultLocale())->translateArgs($message, $args);
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

    public static function now()
    {
        if (self::$_now === null) {
            self::$_now = time();
        }
        return self::$_now;
    }

    public static function getRequest()
    {
        if (self::$_request === null) {
            self::$_request = new Legacies_Core_Controller_Request_Http();
        }
        return self::$_request;
    }

    public static function setRequest($request)
    {
        self::$_request = $request;
    }

    public static function getResponse()
    {
        if (self::$_response === null) {
            self::$_response = new Legacies_Core_Controller_Response();
        }
        return self::$_response;
    }

    public static function setResponse($response)
    {
        self::$_response = $response;
    }
}