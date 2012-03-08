<?php

class Wootook_Core_Exception_Database_AdapterError
    extends Wootook_Core_Exception_RuntimeException
    implements Wootook_Core_Exception_Database_Error
{
    protected $_adapter = null;

    public function __construct(Wootook_Core_Database_Adapter_Adapter $adapter, $message = null, $code = null, $previous = null)
    {
        parent::__construct($message, null, $previous);

        $this->_adapter = $adapter;
    }

    public function getAdapter()
    {
        return $this->_adapter;
    }
}