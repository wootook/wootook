<?php

abstract class Wootook_Core_Database_Adapter_Adapter
{
    protected $_handler = null;

    protected $_tablePrefix = null;

    public function getDriverHandler()
    {
        return $this->_handler;
    }

    public function getDataMapper()
    {
        return new Wootook_Core_Database_Orm_DataMapper();
    }

    /**
     *
     * @param string $prefix
     * @return Wootook_Core_Database_Adapter_Pdo_Mysql
     */
    public function setTablePrefix($prefix)
    {
        $this->_tablePrefix = $prefix;

        return $this;
    }

    /**
     * @return string
     */
    public function getTablePrefix()
    {
        return $this->_tablePrefix;
    }

    /**
     * @return string
     */
    public function getTable($table)
    {
        return $this->getTablePrefix() . $table;
    }

    /**
     * @return Wootook_Core_Database_Sql_Select
     */
    public function select()
    {
        return new Wootook_Core_Database_Sql_Select($this);
    }

    /**
     * @return Wootook_Core_Database_Sql_Insert
     */
    public function insert()
    {
        return new Wootook_Core_Database_Sql_Insert($this);
    }

    /**
     * @return Wootook_Core_Database_Sql_Update
     */
    public function update()
    {
        return new Wootook_Core_Database_Sql_Update($this);
    }

    /**
     * @return Wootook_Core_Database_Sql_Delete
     */
    public function delete()
    {
        return new Wootook_Core_Database_Sql_Delete($this);
    }

    /**
     * @param string $data
     * @return string
     */
    abstract public function quote($data);

    /**
     * @param string $identifier
     * @return string
     */
    abstract public function quoteIdentifier($identifier);

    /**
     * @param string $identifier
     * @return string
     */
    public function quoteInto($string, $values)
    {
        $parts = preg_split('#(:[\w_]+|[?])#', $string, null, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        $result = '';
        if (is_array($values)) {
            $index = 0;
            foreach ($parts as $part) {
                if ($part == '?') {
                    $result .= $this->quote($values[$index++]);
                } else if (!empty($part) && $part[0] == ':') {
                    $key = substr($part, 1);

                    if (isset($values[$key])) {
                        $result .= $this->quote($values[$key]);
                    } else if (isset($values[$part])) {
                        $result .= $this->quote($values[$part]);
                    } else {
                        $result .= $part;
                    }
                } else {
                    $result .= $part;
                }
            }
        } else {
            foreach ($parts as $part) {
                if ($part == '?' || $part[0] == ':') {
                    $result .= $this->quote($values);
                } else {
                    $result .= $part;
                }
            }
        }

        return $result;
    }

    /**
     * @return Wootook_Core_Database_Statement_Statement
     */
    public function query($sql, Array $params = null)
    {
        $statement = $this->prepare($sql, $params);
        if (!$statement->execute()) {
            $message = sprintf('[SQLSTATE %s] Could not execute query: %s', $statement->errorState(), $statement->errorMessage());
            throw new Wootook_Core_Exception_Database_StatementError($statement, $message);
        }

        return $statement;
    }

    /**
     * @return bool
     */
    public function execute($sql, Array $params = null)
    {
        $statement = $this->prepare($sql);
        return $statement->execute($params);
    }

    /**
     * @return Wootook_Core_Database_Statement_Statement
     */
    abstract public function prepare($sql, Array $params = null);

    /**
     * @return bool
     */
    abstract public function beginTransaction();

    /**
     * @return bool
     */
    abstract public function commit();

    /**
     * @return bool
     */
    abstract public function rollback();

    /**
     * @return bool
     */
    abstract public function lastInsertId();

    /**
     * @return string
     */
    abstract public function errorCode();

    /**
     * @return string
     */
    abstract public function errorMessage();

    /**
     * @return array
     */
    abstract public function errorInfo();

    /**
     * @return string
     */
    abstract public function errorState();
}
