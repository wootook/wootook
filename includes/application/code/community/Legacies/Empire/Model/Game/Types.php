<?php

/**
 *
 * Enter description here ...
 *
 * @uses Legacies_Object
 * @uses Legacies_Empire
 */
class Legacies_Empire_Model_Game_Types
    extends Legacies_Empire_Model_Game_Abstract
    implements Legacies_Core_Singleton
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
        foreach (include ROOT_PATH . 'includes/data/types.php' as $elementId => $fieldName) {
            $this->setData($elementId, $fieldName);
        }
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

    public function is($element, $type)
    {
        if (!is_array($this->getData($type))) {
            return false;
        }
        return in_array($element, $this->getData($type));
    }
}