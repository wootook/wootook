<?php

/**
 *
 * Enter description here ...
 *
 * @uses Wootook_Object
 * @uses Legacies_Empire
 */
class Wootook_Core_Helper_Config_Events
    extends Wootook_Core_Helper_Config_ConfigHandler
    implements Wootook_Core_Singleton
{
    private static $_singleton = null;

    public static function getSingleton()
    {
        if (self::$_singleton === null) {
            self::$_singleton = new self();
        }
        return self::$_singleton;
    }

    public static function registerEvents()
    {
        foreach (self::getSingleton() as $event => $listenerList) {
            foreach ($listenerList as $listener) {
                Wootook::registerListener($event, $listener);
            }
        }
    }

    protected function _init()
    {
        $this->_initData('events');
        return $this;
    }

    protected function _load()
    {
        // NOP
        return $this;
    }

    protected function _save()
    {
        // NOP
        return $this;
    }

    protected function _delete()
    {
        // NOP
        return $this;
    }
}