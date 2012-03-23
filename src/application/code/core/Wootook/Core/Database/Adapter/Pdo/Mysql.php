<?php

class Wootook_Core_Database_Adapter_Pdo_Mysql
    extends Wootook_Core_Database_Adapter_Adapter
{
    /**
     *
     * Enter description here ...
     * @param Wootook_Core_Config_Node $config
     * @param array $params
     */
    public function __construct(Wootook_Core_Config_Node $config, Array $options = array())
    {
        $dsn = "mysql:host={$config->hostname};dbname={$config->database}";
        if (is_numeric($config->port)) {
            $dsn .= ";port={$config->database}";
        }

        if (!isset($options[Wootook_Core_Database_ConnectionManager::ATTR_ERRMODE])) {
            $options[Wootook_Core_Database_ConnectionManager::ATTR_ERRMODE] = Wootook_Core_Database_ConnectionManager::ERRMODE_EXCEPTION;
        }

        try {
            $this->_handler = new PDO($dsn, $config->username, $config->password, $options);
        } catch (PDOException $e) {
            throw new Wootook_Core_Exception_Database_AdapterError($this, $e->getMessage(), null, $e);
        }
    }

    /**
     * (non-PHPdoc)
     * @see Wootook_Core_Database_Adapter_Adapter::quoteIdentifier()
     */
    public function quoteIdentifier($identifier)
    {
        return "`$identifier`";
    }

    public function quote($data)
    {
        try {
            return $this->_handler->quote($data);
        } catch (PDOException $e) {
            throw new Wootook_Core_Exception_Database_AdapterError($this, $e->getMessage(), null, $e);
        }
        return null;
    }

    /**
     *
     * @param string $name
     * @return string
     */
    public function getTable($name)
    {
        return $this->getTablePrefix() . $name;
    }

    /**
     * @see Wootook_Core_Database_Adapter_Adapter::select()
     */
    public function select($tableName = null)
    {
        return new Wootook_Core_Database_Sql_Mysql_Select($this, $tableName);
    }

    /**
     * @return Wootook_Core_Database_Statement_Statement
     */
    public function prepare($sql, Array $params = null)
    {
        $statement = new Wootook_Core_Database_Statement_Pdo_Mysql($this, $sql);

        if ($params !== null) {
            foreach ($params as $paramKey => $paramValue) {
                $statement->bindValue($paramKey, $paramValue, $statement->getParamType($paramValue));
            }
        }

        return $statement;
    }

    /**
     * @return bool
     */
    public function beginTransaction()
    {
        try {
            return $this->_handler->beginTransaction();
        } catch (PDOException $e) {
            throw new Wootook_Core_Exception_Database_AdapterError($this, $e->getMessage(), null, $e);
        }
        return false;
    }

    /**
     * @return bool
     */
    public function commit()
    {
        try {
            return $this->_handler->commit();
        } catch (PDOException $e) {
            throw new Wootook_Core_Exception_Database_AdapterError($this, $e->getMessage(), null, $e);
        }
        return false;
    }

    /**
     * @return bool
     */
    public function rollback()
    {
        try {
            return $this->_handler->rollback();
        } catch (PDOException $e) {
            throw new Wootook_Core_Exception_Database_AdapterError($this, $e->getMessage(), null, $e);
        }
        return false;
    }

    /**
     * @return int
     */
    public function lastInsertId()
    {
        try {
            return $this->_handler->lastInsertId();
        } catch (PDOException $e) {
            throw new Wootook_Core_Exception_Database_AdapterError($this, $e->getMessage(), null, $e);
        }
        return false;
    }

    /**
     * @return string
     */
    public function errorCode()
    {
        return $this->_handler->errorCode();
    }

    /**
     * @return array
     */
    public function errorInfo()
    {
        return $this->_handler->errorInfo();
    }

    /**
     * @return string
     */
    public function errorMessage()
    {
        $info = $this->_handler->errorInfo();

        return $info[2];
    }

    /**
     * @return string
     */
    public function errorState()
    {
        $info = $this->_handler->errorInfo();

        return $info[0];
    }
}
