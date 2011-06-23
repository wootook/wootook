<?php

class Legacies_Object
    implements ArrayAccess
{
    protected $_data = array();

    public function __construct(Array $data = array())
    {
        $this->_data = $data;
    }

    public function getData($key)
    {
        if ($this->hasData($key)) {
            return $this->_data[$key];
        }
        return null;
    }

    public function getAllDatas()
    {
        return $this->_data;
    }

    public function hasData($key)
    {
        return (bool) isset($this->_data[$key]);
    }

    public function setData($key, $value)
    {
        $this->_data[$key] = $value;

        return $this;
    }

    public function addData(Array $data)
    {
        foreach ($data as $key => $value) {
            $this->setData($key, $value);
        }

        return $this;
    }

    public function unsetData($key)
    {
        if ($this->hasData($key)) {
            unset($this->_data[$key]);
        }

        return $this;
    }

    public function clearData()
    {
        $this->_data = array();

        return $this;
    }

    public function offsetExists($offset)
    {
        return $this->hasData($offset);
    }

    public function offsetGet($offset)
    {
        return $this->getData($offset);
    }

    public function offsetSet($offset, $data)
    {
        return $this->setData($offset, $data);
    }

    public function offsetUnset($offset)
    {
        return $this->unsetData($offset);
    }

    public function __set($key, $value)
    {
        return $this->setData($key, $value);
    }

    public function __get($key)
    {
        return $this->getData($key);
    }

    public function __unset($key)
    {
        return $this->unsetData($key);
    }

    public function __isset($key)
    {
        return $this->hasData($key);
    }
}