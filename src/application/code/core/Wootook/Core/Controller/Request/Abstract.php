<?php

abstract class Wootook_Core_Controller_Request_Abstract
    extends Wootook_Object
{
    public function setParam($key, $value)
    {
        return $this->setData($key, $value);
    }

    public function getParam($key, $default = null)
    {
        if ($this->hasData($key)) {
            return $this->getData($key);
        }
        return $default;
    }

    abstract public function getModuleName();
    abstract public function getControllerName();
    abstract public function getActionName();

    abstract public function setModuleName($name);
    abstract public function setControllerName($name);
    abstract public function setActionName($name);
}
