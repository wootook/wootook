<?php

class Wootook_Core_Database_Sql_Placeholder_Param
    extends Wootook_Core_Database_Sql_Placeholder_Placeholder
{
    protected $_paramType = null;
    protected $_paramName = null;
    protected $_value = null;

    public function __construct($paramName, $value, $type = null)
    {
        $this->_paramName = $paramName;
        $this->_paramType = $type;
        $this->_value = $value;
    }

    public function __toString()
    {
        return ':' . $this->_paramName;
    }

    public function beforeExecute(Wootook_Core_Database_Statement_Statement $statement)
    {
        parent::beforeExecute($statement);

        $type = $this->_paramType !== null ? $this->_paramType : $statement->getParamType($this->_value);
        $statement->bindValue($this->_paramName, $this->_value, $type);

        return $this;
    }
}
