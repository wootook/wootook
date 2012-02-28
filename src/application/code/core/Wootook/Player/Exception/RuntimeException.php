<?php

class Wootook_Player_Exception_RuntimeException
    extends RuntimeException
    implements Wootook_Empire_Exception
{
    protected $_previous = null;

    public function __construct($message = null, $code = null, $previous = null)
    {
        if (PHP_VERSION_ID >= 50300) {
            parent::__construct($message, $code, $previous);
        } else {
            parent::__construct($message, $code);
            $this->_previous = $previous;
        }
    }

    public function __call($method, $params)
    {
        if (strtolower($method) == 'getprevious') {
            return $this->_previous;
        }
    }
}