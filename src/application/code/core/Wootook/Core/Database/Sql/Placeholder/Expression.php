<?php

class Wootook_Core_Database_Sql_Placeholder_Expression
    extends Wootook_Core_Database_Sql_Placeholder_Placeholder
{
    protected $_expression = null;
    protected $_params = array();

    public function __construct($expression, Array $params = array())
    {
        $this->_expression = (string) $expression;
        $this->_params = $params;
    }

    public function __toString()
    {
        return $this->_expression;
    }

    public function beforeExecute(Wootook_Core_Database_Statement_Statement $statement)
    {
        parent::beforeExecute($statement);

        foreach ($this->_params as $paramName => $value) {
            $statement->bindValue($paramName, $value, $statement->getParamType($value));
        }

        return $this;
    }
}
