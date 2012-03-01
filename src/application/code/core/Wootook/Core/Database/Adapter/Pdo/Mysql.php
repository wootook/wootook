<?php

class Wootook_Core_Database_Adapter_Pdo_Mysql
    extends Wootook_Core_Database_Adapter_Adapter
{
    protected $_tablePrefix = null;
    protected $_dsn = null;
    protected $_username = null;
    protected $_password = null;

    public function quoteIdentifier($identifier)
    {
        return "`$identifier`";
    }

    public function setTablePrefix($prefix)
    {
        $this->_tablePrefix = $prefix;

        return $this;
    }

    public function getTablePrefix()
    {
        return $this->_tablePrefix;
    }

    public function getTable($name)
    {
        return $this->getTablePrefix() . $name;
    }

    public function select($tableName = null)
    {
        return new Wootook_Core_Database_Sql_Mysql_Select($this, $tableName);
    }
}