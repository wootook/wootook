<?php

abstract class Wootook_Core_Helper_Config_ConfigHandler
    extends Wootook_Core_Mvc_Model_Model
    implements Iterator, Countable
{
    protected function _initData($filename)
    {
        $config = Wootook::getGameConfig('engine/storyline');

        if ($config === null) {
            $config = array(
                'universe' => 'default',
                'episode'  => 'default'
                );
        } else {
            $config = $config->toArray();
        }

        $path = 'gamedata' . DIRECTORY_SEPARATOR . $config['universe'] . DIRECTORY_SEPARATOR
            . $config['episode'] . DIRECTORY_SEPARATOR . $filename . '.php';

        foreach (include APPLICATION_PATH . $path as $elementId => $fieldName) {
            $this->setData($elementId, $fieldName);
        }

        return $this;
    }

    public function count()
    {
        return count($this->_data);
    }

    public function current()
    {
        return current($this->_data);
    }

    public function next()
    {
        return next($this->_data);
    }

    public function key()
    {
        return key($this->_data);
    }

    public function valid()
    {
        return $this->current() !== false;
    }

    public function rewind()
    {
        reset($this->_data);
    }
}
