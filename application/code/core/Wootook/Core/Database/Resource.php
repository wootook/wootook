<?php

abstract class Wootook_Core_Database_Resource
    extends Wootook_Core_Model
{
    protected $_readConnection = null;
    protected $_writeConnection = null;

    protected $_tableName = null;

    public function setTableName($tableName)
    {
        $this->_tableName = $tableName;

        return $this;
    }

    public function getTableName()
    {
        return $this->_tableName;
    }

    public function getReadConnection()
    {
        if ($this->_readConnection === null) {
            $this->_readConnection = Wootook_Database::getConnection('core_read');
        }

        return $this->_readConnection;
    }

    public function setReadConnection($connection)
    {
        if ($connection instanceof Wootook_Database) {
            $this->_readConnection = $connection;
        } else if (is_string($connection)) {
            $this->_readConnection = Wootook_Database::getConnection($connection);
        } else {
            throw new Wootook_Core_Exception_RuntimeException(
                'First parameter should be either a database connection object or a string identifier.');
        }

        return $this;
    }

    public function getWriteConnection()
    {
        if ($this->_writeConnection === null) {
            $this->_writeConnection = Wootook_Database::getConnection('core_write');
        }
        return $this->_writeConnection;
    }

    public function setWriteConnection($connection)
    {
        if ($connection instanceof Wootook_Database) {
            $this->_readConnection = $connection;
        } else if (is_string($connection)) {
            $this->_readConnection = Wootook_Database::getConnection($connection);
        } else {
            throw new Wootook_Core_Exception_RuntimeException(
                'First parameter should be either a database connection object or a string identifier.');
        }

        return $this;
    }
}