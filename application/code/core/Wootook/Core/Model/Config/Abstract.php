<?php

abstract class Wootook_Core_Model_Config_Abstract
    extends Wootook_Core_Model
    implements Iterator, Countable
{
    protected function _initData($filename)
    {
        $config = Wootook::getConfig('global/storyline');

        if ($config === null || empty($config)) {
            $config = array(
                'universe' => 'default',
                'episode'  => 'default'
                );
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