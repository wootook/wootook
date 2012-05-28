<?php

class Wootook_Core_Database_Statement_Pdo_Mysql
    extends Wootook_Core_Database_Statement_Statement
{
    /**
     * @var PDOStatement
     */
    protected $_handler = null;

    protected $_query = null;

    protected function _init($query)
    {
        $this->_query = $query;

        if ($this->_query instanceof Wootook_Core_Database_Sql_Dml) {
            $this->_query->beforePrepare($this);
        }

        try {
            $this->_handler = $this->_adapter->getDriverHandler()->prepare($this->_query);
        } catch (PDOException $e) {
            throw new Wootook_Core_Exception_Database_StatementError($this, $e->getMessage(), null, $e);
        }

        if ($this->_query instanceof Wootook_Core_Database_Sql_Dml) {
            $this->_query->afterPrepare($this);
        }

        return $this;
    }

    /**
     *
     * @param string|int $column
     * @param mixed $param
     * @param int $type
     * @return Wootook_Core_Database_Statement_Statement
     */
    public function bindColumn($column, &$param, $type = null)
    {
        try {
            return $this->_handler->bindColumn($column, $param, $type);
        } catch (PDOException $e) {
            throw new Wootook_Core_Exception_Database_StatementError($this, $e->getMessage(), null, $e);
        }

        return false;
    }

    /**
     *
     * @param string|int $parameter
     * @param mixed $variable
     * @param int $type
     * @param int $length
     * @param unknown_type $options
     * @return Wootook_Core_Database_Statement_Statement
     */
    public function bindParam($parameter, &$variable, $type = null, $length = null, $options = null)
    {
        try {
            return $this->_handler->bindParam($parameter, $variable, $type, $length, $options);
        } catch (PDOException $e) {
            throw new Wootook_Core_Exception_Database_StatementError($this, $e->getMessage(), null, $e);
        }
        return false;
    }

    /**
     *
     * @param string|int $parameter
     * @param mixed $value
     * @param int $type
     * @return Wootook_Core_Database_Statement_Statement
     */
    public function bindValue($parameter, $value, $type = null)
    {
        try {
            return $this->_handler->bindValue($parameter, $value, $type);
        } catch (PDOException $e) {
            throw new Wootook_Core_Exception_Database_StatementError($this, $e->getMessage(), null, $e);
        }
        return false;
    }

    /**
     *
     * @param array $params
     * @return bool
     */
    public function execute(Array $params = null)
    {
        if ($this->_query instanceof Wootook_Core_Database_Sql_Dml) {
            $this->_query->beforeExecute($this);
        }

        try {
            $result = $this->_handler->execute($params);
        } catch (PDOException $e) {
            throw new Wootook_Core_Exception_Database_StatementError($this, $e->getMessage(), null, $e);
        }

        if ($this->_query instanceof Wootook_Core_Database_Sql_Dml) {
            $this->_query->afterExecute($this);
        }

        return $result;
    }

    /**
     *
     * @param int $style
     * @param int $col
     * @return mixed
     */
    public function fetchAll($style = null, $col = null)
    {
        try {
            if ($style !== null) {
                if ($col !== null) {
                    $result = $this->_handler->fetchAll($style, $col);
                } else {
                    $result = $this->_handler->fetchAll($style);
                }
            } else {
                $result = $this->_handler->fetchAll($style);
            }
        } catch (PDOException $e) {
            throw new Wootook_Core_Exception_Database_StatementError($this, $e->getMessage(), null, $e);
        }
        return $result;
    }

    /**
     *
     * @param int $col
     * @return mixed
     */
    public function fetchColumn($col = 0)
    {
        try {
            return $this->_handler->fetchColumn($col);
        } catch (PDOException $e) {
            throw new Wootook_Core_Exception_Database_StatementError($this, $e->getMessage(), null, $e);
        }
        return null;
    }

    /**
     *
     * @return Wootook_Core_Database_Adapter_Adapter
     */
    public function getAdapter()
    {
        return $this->_adapter;
    }

    /**
     *
     * @param string $key
     */
    public function getAttribute($key)
    {
        try {
            return $this->_handler->getAttribute($key);
        } catch (PDOException $e) {
            throw new Wootook_Core_Exception_Database_StatementError($this, $e->getMessage(), null, $e);
        }
        return null;
    }

    /**
     *
     * @param string $key
     * @param mixed $value
     * @return Wootook_Core_Database_Statement_Statement
     */
    public function setAttribute($key, $value)
    {
        try {
            return $this->_handler->setAttribute($key, $value);
        } catch (PDOException $e) {
            throw new Wootook_Core_Exception_Database_StatementError($this, $e->getMessage(), null, $e);
        }
        return false;
    }

    /**
     * @param unknown_type $mode
     * @return Wootook_Core_Database_Statement_Statement
     */
    public function setFetchMode($mode)
    {
        try {
            $params = func_get_args();
            return call_user_func_array($array($this->_handler, 'setFetchMode'), $params);
        } catch (PDOException $e) {
            throw new Wootook_Core_Exception_Database_StatementError($this, $e->getMessage(), null, $e);
        }
        return false;
    }

    /**
     * @return int
     */
    public function columnCount()
    {
        try {
            return $this->_handler->columnCount();
        } catch (PDOException $e) {
            throw new Wootook_Core_Exception_Database_StatementError($this, $e->getMessage(), null, $e);
        }
        return 0;
    }

    /**
     * @return int
     */
    public function rowCount()
    {
        try {
            return $this->_handler->rowCount();
        } catch (PDOException $e) {
            throw new Wootook_Core_Exception_Database_StatementError($this, $e->getMessage(), null, $e);
        }
        return 0;
    }

    /**
     * @param int $col
     * @return mixed
     */
    public function fetch($style = null, $orientation = Wootook_Core_Database_ConnectionManager::FETCH_ORI_NEXT, $cursorOffset = 0)
    {
        try {
            return $this->_handler->fetch($style, $orientation, $cursorOffset);
        } catch (PDOException $e) {
            throw new Wootook_Core_Exception_Database_StatementError($this, $e->getMessage(), null, $e);
        }
        return null;
    }

    /**
     * @param string $class
     * @param array $config
     * @return Wootook_Object
     */
    public function fetchObject($class = 'Wootook_Object', Array $constructorArgs = array())
    {
        try {
            return $this->_handler->fetchObject($class, $constructorArgs);
        } catch (PDOException $e) {
            throw new Wootook_Core_Exception_Database_StatementError($this, $e->getMessage(), null, $e);
        }
        return null;
    }

    /**
     * @return bool
     */
    public function closeCursor()
    {
        try {
            return $this->_handler->closeCursor();
        } catch (PDOException $e) {
            throw new Wootook_Core_Exception_Database_AdapterError($this, $e->getMessage(), null, $e);
        }
        return false;
    }

    /**
     * @return bool
     */
    public function nextRowset()
    {
        try {
            return $this->_handler->nextRowset();
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
