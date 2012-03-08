<?php

abstract class Wootook_Core_Database_Adapter_Adapter
{
    protected $_handler = null;

    protected $_tablePrefix = null;

    public function getDriverHandler()
    {
        return $this->_handler;
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
        $parts = preg_split('#(\?|:[\w_]+)#', $identifier, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        $result = '';
        if (is_array($values)) {
            $index = 0;
            foreach ($parts as $part) {
                if ($part == '?') {
                    $result .= $this->quote($values[$index++]);
                } else if ($part[0] == ':') {
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
    public function query($sql)
    {
        $statement = $this->prepare($sql);
        if (!$statement->execute()) {
            throw new Wootook_Core_Exception_Database_AdapterError($this, 'Could not execute query.');
        }

        return $statement;
    }

    /**
     * @return bool
     */
    public function execute($sql)
    {
        $statement = $this->prepare($sql);
        return $statement->execute();
    }

    /**
     * @return Wootook_Core_Database_Statement_Statement
     */
    abstract public function prepare($sql);

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
}