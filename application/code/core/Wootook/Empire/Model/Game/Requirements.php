<?php

/**
 *
 * Enter description here ...
 *
 * @uses Wootook_Object
 * @uses Legacies_Empire
 */
class Wootook_Empire_Model_Game_Requirements
    extends Wootook_Core_Model_Config_Abstract
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

    protected function _init()
    {
        $this->_initData('requirements');
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