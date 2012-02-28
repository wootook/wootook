<?php

abstract class Wootook_Core_Database_Sql_Select
{
    const COLUMNS = 'COLUMNS';
    const FROM    = 'FROM';
    const JOIN    = 'JOIN';
    const WHERE   = 'WHERE';
    const ORDER   = 'ORDER';
    const UNION   = 'UNION';
    const LIMIT   = 'LIMIT';
    const OFFSET  = 'OFFSET';
    const GROUP   = 'GROUP';

    const JOIN_INNER = 'INNER';
    const JOIN_OUTER = 'OUTER';
    const JOIN_LEFT  = 'LEFT';
    const JOIN_RIGHT = 'RIGHT';

    protected $_parts = array(
        self::COLUMNS => array(),
        self::FROM    => array(),
        self::JOIN    => array(),
        self::WHERE   => array(),
        self::ORDER   => array(),
        self::UNION   => array(),
        self::LIMIT   => array(),
        self::OFFSET  => array(),
        self::GROUP   => array(),
        );

    protected $_connection = null;

    public function __construct(Wootook_Core_Database $connection, $tableName = null)
    {
        $this->_connection = $connection;

        if ($tableName !== null) {
            $this->from($tableName);
        }
    }

    public function getConnection()
    {
        return $this->_connection;
    }

    public function setConnection(Wootook_Core_Database $connection)
    {
        $this->_connection = $connection;

        return $this;
    }

    protected function _init()
    {
        return $this;
    }

    public function setTableName($tableName)
    {
        $this->from($tableName);

        return $this;
    }

    public function getTableName()
    {
        $table = $this->_parts[self::FROM];

        return $table['table'];
    }

    public function quote($data)
    {
        return $this->getConnection()->quote($data);
    }

    public function column($column = '*', $alias = null)
    {
        if (is_array($column)) {
            foreach ($column as $alias => $field) {
                if (is_int($alias)) {
                    $this->_parts[self::COLUMNS][] = array($field);
                } else {
                    $this->_parts[self::COLUMNS][] = array($alias => $field);
                }
            }
        } else if ($alias === null || is_int($alias)) {
            $this->_parts[self::COLUMNS][] = array($column);
        } else {
            $this->_parts[self::COLUMNS][] = array($alias => $column);
        }

        return $this;
    }

    public function from($table, $schema = null)
    {
        if (is_array($table)) {
            $alias = key($table);
            $table = current($table);
        } else {
            $alias = null;
        }

        $this->_parts[self::FROM][] = array(
            'table'  => $table,
            'alias'  => $alias,
            'schema' => $schema
            );

        return $this;
    }

    public function where($condition)
    {
        $this->_parts[self::WHERE][] = $condition;

        return $this;
    }

    public function join($table, $condition, $fields = array('*'), $mode = self::JOIN_INNER)
    {
        $database = $this->getConnection();

        if (is_array($table)) {
            $alias = key($table);
            $table = current($table);

            foreach ($fields as $fieldAlias => $fieldName) {
                $this->column("{$alias}.{$field}", $fieldAlias);
            }

            $this->_parts[self::JOIN][] = "{$mode} JOIN {$database->getTable($table)} AS {$alias} ON {$condition}";
        } else {
            foreach ($fields as $field) {
                $this->column("{$field}");
            }

            $this->_parts[self::JOIN][] = "{$mode} JOIN {$database->getTable($table)} ON {$condition}";
        }

        return $this;
    }

    public function joinLeft($table, $condition, $fields = array('*'))
    {
        return $this->join($table, $condition, $fields, self::JOIN_LEFT);
    }

    public function joinRight($table, $condition, $fields = array('*'))
    {
        return $this->join($table, $condition, $fields, self::JOIN_RIGHT);
    }

    public function joinInner($table, $condition, $fields = array('*'))
    {
        return $this->join($table, $condition, $fields, self::JOIN_INNER);
    }

    public function joinOuter($table, $condition, $fields = array('*'))
    {
        return $this->join($table, $condition, $fields, self::JOIN_OUTER);
    }

    public function order($field, $direction = 'ASC')
    {
        $this->_parts[self::ORDER][] = "{$field} {$direction}";

        return $this;
    }

    public function union($collection)
    {
        $this->_parts[self::UNION][] = $collection;

        return $this;
    }

    public function limit($limit, $offset = null)
    {
        $this->_parts[self::LIMIT] = intval($limit);
        $this->_parts[self::OFFSET] = $offset;

        return $this;
    }

    public function group($groupField)
    {
        $this->_parts[self::GROUP][] = $groupField;

        return $this;
    }

    abstract protected function _prepareSql();

    public function __toString()
    {
        return $this->_prepareSql();
    }

    public function toString()
    {
        return $this->_prepareSql();
    }
}