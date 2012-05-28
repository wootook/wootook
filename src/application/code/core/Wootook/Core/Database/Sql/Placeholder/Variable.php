<?php

class Wootook_Core_Database_Sql_Placeholder_Variable
    extends Wootook_Core_Database_Sql_Placeholder_Placeholder
{
    protected $_paramType = null;
    protected $_paramName = null;
    protected $_value = null;

    public function __construct($paramName, $type = null)
    {
        $this->_paramName = $paramName;
        $this->_paramType = $type;
    }

    public function __toString()
    {
        return ':' . $this->_paramName;
    }
}
