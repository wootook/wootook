<?php

abstract class Wootook_Core_Database_Resource
    extends Wootook_Core_Mvc_Model_Model
{
    protected $_readConnection = null;
    protected $_writeConnection = null;

    protected $_tableName = null;

    protected $_dataMapper = null;

    /**
     *
     * @return Wootook_Core_Database_Orm_DataMapper
     */
    public function getDataMapper()
    {
        if ($this->_dataMapper === null) {
            $this->_dataMapper = new Wootook_Core_Database_Orm_DataMapper();
        }
        return $this->_dataMapper;
    }

    public function setTableName($tableName)
    {
        $this->_tableName = $tableName;

        return $this;
    }

    public function getTableName()
    {
        return $this->_tableName;
    }

    /**
     * @return Wootook_Core_Database_Adapter_Adapter
     */
    public function getReadConnection()
    {
        if ($this->_readConnection === null) {
            $this->_readConnection = Wootook_Core_Database_ConnectionManager::getSingleton()
                ->getConnection('core_read');
        }

        return $this->_readConnection;
    }

    public function setReadConnection($connection)
    {
        if ($connection instanceof Wootook_Core_Database_Adapter_Pdo_Mysql) {
            $this->_readConnection = $connection;
        } else if (is_string($connection)) {
            $this->_readConnection = $this->getConnection($connection);
        } else {
            throw new Wootook_Core_Exception_RuntimeException(
                'First parameter should be either a database connection object or a string identifier.');
        }

        return $this;
    }

    /**
     * @return Wootook_Core_Database_Adapter_Adapter
     */
    public function getWriteConnection()
    {
        if ($this->_writeConnection === null) {
            $this->_writeConnection = Wootook_Core_Database_ConnectionManager::getSingleton()
                ->getConnection('core_write');
        }
        return $this->_writeConnection;
    }

    public function setWriteConnection($connection)
    {
        if ($connection instanceof Wootook_Core_Database_Adapter_Pdo_Mysql) {
            $this->_readConnection = $connection;
        } else if (is_string($connection)) {
            $this->_readConnection = $this->getConnection($connection);
        } else {
            throw new Wootook_Core_Exception_RuntimeException(
                'First parameter should be either a database connection object or a string identifier.');
        }

        return $this;
    }
}
