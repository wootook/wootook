<?php

abstract class Wootook_Core_Database_Sql_Select
    implements Wootook_Core_Database_Sql_Dml
{
    const COLUMNS = 'COLUMNS';
    const FROM    = 'FROM';
    const JOIN    = 'JOIN';
    const ORDER   = 'ORDER';
    const UNION   = 'UNION';
    const GROUP   = 'GROUP';
    const HAVING  = 'HAVING';

    const JOIN_INNER = 'INNER';
    const JOIN_OUTER = 'OUTER';
    const JOIN_LEFT  = 'LEFT';
    const JOIN_RIGHT = 'RIGHT';

    protected function _init($tableName = null)
    {
        if ($tableName !== null) {
            $this->from($tableName);
        }

        return $this;
    }

    /**
     * @param string $tableName
     * @deprecated
     */
    public function setTableName($tableName)
    {
        $this->from($tableName);

        return $this;
    }

    /**
     * @deprecated
     */
    public function getTableName()
    {
        $table = current($this->_parts[self::FROM]);

        return $table['table'];
    }

    public function reset($part = null)
    {
        if ($part === null) {
            $this->_parts = array(
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
        } else if (isset($this->_parts[$part])) {
            $this->_parts[$part] = array();
        }

        return $this;
    }

    public function column($column = '*', $table = null)
    {
        if (is_array($column)) {
            foreach ($column as $alias => $field) {
                if (is_int($alias)) {
                    $this->_parts[self::COLUMNS][] = array(
                        'table' => $table,
                        'alias' => null,
                        'field' => $field
                        );
                } else {
                    $this->_parts[self::COLUMNS][] = array(
                        'table' => $table,
                        'alias' => $alias,
                        'field' => $field
                        );
                }
            }
        } else {
            $this->_parts[self::COLUMNS][] = array(
                'table' => $table,
                'alias' => null,
                'field' => $column
                );
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

    public function group($groupField)
    {
        $this->_parts[self::GROUP][] = $groupField;

        return $this;
    }

    public function __toString()
    {
        return $this->render();
    }

    public function toString($part = null)
    {
        if ($part === null) {
            return $this->render();
        }

        switch ($part) {
        case self::COLUMNS:
            return $this->renderColumns();
            break;
        case self::FROM:
            return $this->renderFrom();
            break;
        case self::WHERE:
            return $this->renderWhere();
            break;
        case self::JOIN:
            return $this->renderJoin();
            break;
        case self::ORDER:
            return $this->renderOrder();
            break;
        case self::UNION:
            return $this->renderUnion();
            break;
        case self::LIMIT:
            return $this->renderLimit();
            break;
        case self::GROUP:
            return $this->renderGroup();
            break;
        case self::HAVING:
            return $this->renderHaving();
            break;
        }

        return null;
    }

    abstract public function renderColumns();
    abstract public function renderFrom();
    abstract public function renderJoin();
    abstract public function renderOrder();
    abstract public function renderUnion();
    abstract public function renderGroup();
    abstract public function renderHaving();
}