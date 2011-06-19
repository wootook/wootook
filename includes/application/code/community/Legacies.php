<?php

class Legacies
{
    private static $_listeners = array();

    private static $_translators = array();

    public static $request = null;
    public static $response = null;

    public static function registerListener($event, $listener)
    {
        if (!isset(self::$_listeners[$event])) {
            self::$_listeners[$event] = array();
        }
        self::$_listeners[$event][] = $listener;
    }

    public static function clearAllListeners()
    {
        self::$_listeners = array();
    }

    public static function clearEventListeners($event)
    {
        if (isset(self::$_listeners[$event])) {
            self::$_listeners[$event] = array();
        }
    }

    public static function dispatchEvent($event, $params)
    {
        if (!isset(self::$_listeners[$event])) {
            return;
        }

        $eventObject = new Legacies_Core_Event($params);
        foreach (self::$_listeners[$event] as $listener) {
            call_user_func($listener, $eventObject);
        }
    }

    public static function getSession($namespace)
    {
        return Legacies_Core_Model_Session::factory($namespace);
    }

    public static function getTranslator($locale = 'fr_FR')
    {
        if (!isset($translator[$locale])) {
            $path = dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'locale';
            $translator[$locale] = new Legacies_Core_Model_Translator($path, $locale);
        }
        return $translator[$locale];
    }

    public static function translate($locale, $message, Array $args)
    {
        return vsprintf(self::getTranslator($locale)->translateArgs($message), $args);
    }

    public static function __($message, $_ = null)
    {
        $args = func_get_args();
        array_shift($args);

        return self::getTranslator(self::getLocale())->translate($message, $args);
    }

    public static function getLocale()
    {
        return 'fr_FR';
    }
}