<?php

class Wootook_Core_Config_Adapter_Array
    extends Wootook_Core_Config_Adapter_Adapter
{
    public function __construct($filename = null)
    {
        if ($filename !== null) {
            $this->load($filename);
        }
    }

    public function load($filename)
    {
        if (!file_exists($filename)) {
            throw new Wootook_Core_Exception_DataAccessException(sprintf('Could not load config file "%s"', $filename));
        }
        $data = include $filename;

        if (!is_array($data)) {
            throw new Wootook_Core_Exception_DataAccessException('Configuration file could not be loaded.');
        }

        $this->_init($data);

        return $this;
    }

    public function save($filename)
    {
        file_put_contents($filename, '<' . '?p' . 'hp return ' . var_export($this->toArray(), true) . ';');

        return $this;
    }
}