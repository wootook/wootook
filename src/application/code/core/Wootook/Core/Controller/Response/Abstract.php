<?php

abstract class Wootook_Core_Controller_Response_Abstract
    extends Wootook_Object
{
    protected $_isDispatched = false;

    public function setIsDispatched($dispatched = true)
    {
        $this->_isDispatched = $dispatched;

        return $this;
    }

    public function isDispatched()
    {
        return $this->_isDispatched;
    }

    abstract public function render($send = true);
}
