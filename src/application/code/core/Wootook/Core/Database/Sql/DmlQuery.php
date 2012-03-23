<?php

abstract class Wootook_Core_Database_Sql_DmlQuery
    implements Wootook_Core_Database_Sql_Dml
{
    protected $_parts = array();

    protected $_connection = null;

    protected $_placeholders = array();

    public function __construct(Wootook_Core_Database_Adapter_Adapter $connection, $tableName = null)
    {
        $this->setConnection($connection);

        $this->reset();
        $this->_init($tableName);
    }

    /**
     * @return Wootook_Core_Database_Adapter_Adapter
     */
    public function getConnection()
    {
        return $this->_connection;
    }

    public function setConnection(Wootook_Core_Database_Adapter_Adapter $connection)
    {
        $this->_connection = $connection;

        return $this;
    }

    protected function _init($param = null)
    {
        return $this;
    }

    public function getPart($part = null)
    {
        if ($part === null) {
            return $this->_parts;
        }
        if (isset($this->_parts[$part])) {
            return $this->_parts[$part];
        }

        return null;
    }

    public function quote($data)
    {
        return $this->getConnection()->quote($data);
    }

    public function quoteIdentifier($identifier)
    {
        return $this->getConnection()->quoteIdentifier($identifier);
    }

    public function beforePrepare(Wootook_Core_Database_Statement_Statement $statement)
    {
        foreach ($this->_placeholders as $placeholder) {
            $placeholder->beforePrepare($statement);
        }

        return $this;
    }

    public function afterPrepare(Wootook_Core_Database_Statement_Statement $statement)
    {
        foreach ($this->_placeholders as $placeholder) {
            $placeholder->afterPrepare($statement);
        }

        return $this;
    }

    public function beforeExecute(Wootook_Core_Database_Statement_Statement $statement)
    {
        foreach ($this->_placeholders as $placeholder) {
            $placeholder->beforeExecute($statement);
        }

        return $this;
    }

    public function afterExecute(Wootook_Core_Database_Statement_Statement $statement)
    {
        foreach ($this->_placeholders as $placeholder) {
            $placeholder->afterExecute($statement);
        }

        return $this;
    }

    public function prepare()
    {
        return $this->getConnection()->prepare($this);
    }

    public function execute(Array $params = null)
    {
        return $this->getConnection()->execute($this, $params);
    }
}
