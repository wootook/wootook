<?php

abstract class Wootook_Core_Controller_Response_Abstract
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
}
