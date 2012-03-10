<?php

class Wootook_Core_Exception_Database_StatementError
    extends Wootook_Core_Exception_RuntimeException
    implements Wootook_Core_Exception_Database_Error
{
    protected $_statement = null;

    public function __construct(Wootook_Core_Database_Statement_Statement $statement, $message = null, $code = null, $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->_statement = $statement;
    }

    public function getStatement()
    {
        return $this->_statement;
    }
}