<?php

class Wootook_Core_Database_Sql_Placeholder_Expression
    extends Wootook_Core_Database_Sql_Placeholder_Placeholder
{
    protected $_expression = null;

    public function __construct($expression)
    {
        $this->_expression = (string) $expression;
    }

    public function __toString()
    {
        return $this->_expression;
    }
}