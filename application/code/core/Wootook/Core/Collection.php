<?php

class Wootook_Core_Collection
    extends Wootook_Core_Database_Resource
    implements Iterator, Countable
{
    protected $_tableName = null;
    protected $_tableAlias = null;
    protected $_entityClassName = 'Wootook_Object';

    protected $_columns = array();
    protected $_where = array();
    protected $_join = array();
    protected $_order = array();
    protected $_union = array();
    protected $_limit = null;
    protected $_offset = null;
    protected $_group = array();

    protected $_items = array();

    public function __construct($tableName = null, $entityClassName = null)
    {
        if ($tableName !== null) {
            $this->setTableName($tableName);
        }
        if ($entityClassName != null) {
            $this->setEntityClassName($entityClassName);
        }
    }

    protected function _init()
    {
        return $this;
    }

    public function setTableName($tableName)
    {
        if (!is_array($tableName)) {
            $this->_tableName = $tableName;
        } else {
            $this->_tableName = current($tableName);
            $this->_tableAlias = key($tableName);
        }

        return $this;
    }

    public function getTableName()
    {
        return $this->_tableName;
    }

    public function setEntityClassName($entityClassName)
    {
        $this->_entityClassName = $entityClassName;

        return $this;
    }

    public function getEntityClassName()
    {
        return $this->_entityClassName;
    }

    public function quote($data)
    {
        static $database = null;

        if ($database === null) {
            $database = Wootook_Database::getSingleton();
        }

        return $database->quote($data);
    }

    public function column($column = '*', $alias = null)
    {
        if (is_array($column)) {
            foreach ($column as $alias => $field) {
                if (is_int($alias)) {
                    $this->_columns[] = array($field);
                } else {
                    $this->_columns[] = array($alias => $field);
                }
            }
        } else if ($alias === null || is_int($alias)) {
            $this->_columns[] = array($column);
        } else {
            $this->_columns[] = array($alias => $column);
        }

        return $this;
    }

    public function where($condition)
    {
        $this->_where[] = $condition;

        return $this;
    }

    public function join($table, $condition, $fields = array('*'), $mode = 'INNER')
    {
        static $database = null;

        if ($database === null) {
            $database = Wootook_Database::getSingleton();
        }

        if (is_array($table)) {
            $alias = key($table);
            $table = current($table);

            foreach ($fields as $fieldAlias => $fieldName) {
                $this->column("{$alias}.{$field}", $fieldAlias);
            }

            $this->_join[] = "{$mode} JOIN {$database->getTable($table)} AS {$alias} ON {$condition}";
        } else {
            foreach ($fields as $field) {
                $this->column("{$field}");
            }

            $this->_join[] = "{$mode} JOIN {$database->getTable($table)} ON {$condition}";
        }

        return $this;
    }

    public function order($field, $direction = 'ASC')
    {
        $this->_order[] = "{$field} {$direction}";

        return $this;
    }

    public function union($collection)
    {
        $this->_union[] = $collection;

        return $this;
    }

    public function limit($limit, $offset = null)
    {
        $this->_limit = intval($limit);
        $this->_offset = $offset;

        return $this;
    }

    public function group($groupField)
    {
        $this->_group[] = $groupField;

        return $this;
    }

    protected function _prepareSql()
    {
        if (empty($this->_union)) {
            $database = Wootook_Database::getSingleton();

            $fields = array();
            foreach ($this->_columns as $field) {
                $fieldName = current($field);
                $fieldAlias = key($field);
                if (is_string($fieldAlias)) {
                    $fields[] = "{$fieldName} AS {$fieldAlias}";
                } else {
                    $fields[] = $fieldName;
                }
            }
            if (!empty($fields)) {
                $fields = implode(", ", $fields);
            } else {
                $fields = '*';
            }
            if (!empty($this->_where)) {
                $where = 'WHERE (' . implode(") AND (", $this->_where) . ')';
            } else {
                $where = '';
            }
            $joinedTables = implode("\n ", $this->_join);

            $order = '';
            if (!empty($this->_order)) {
                $order = 'ORDER BY ' . implode(", ", $this->_order);
            }
            $alias = '';
            if ($this->_tableAlias !== null) {
                $alias = " AS {$this->_tableAlias}";
            }
            $limit = '';
            if ($this->_limit) {
                if ($this->_offset) {
                    $limit = "LIMIT {$this->_limit}, {$this->_offset}";
                } else {
                    $limit = "LIMIT {$this->_limit}";
                }
            }
            $group = '';
            if (!empty($this->_group)) {
                $group = 'GROUP BY ' . implode(', ', $this->_group);
            }

            return <<<SQL_EOF
SELECT {$fields}
    FROM {$database->getTable($this->getTableName())}{$alias}
    {$joinedTables}
    {$where}
    {$group}
    {$order}
    {$limit}
SQL_EOF;
        } else {
            $statements = array();
            foreach ($this->_union as $statement) {
                $statements[] = $statement->_prepareSql();
            }

            $statements = '(' . implode(') UNION (', $statements) . ')';
            if (!empty($where)) {
                $where = 'WHERE ' . implode(" AND ", $this->_where);
            } else {
                $where = '';
            }
            $where = implode(" AND ", $this->_where);

            $limit = '';
            if ($this->_limit) {
                if ($this->_offset) {
                    $limit = "LIMIT {$this->_limit}, {$this->_offset}";
                } else {
                    $limit = "LIMIT {$this->_limit}";
                }
            }

            return <<<SQL_EOF
$statements
    {$where}
    {$limit}
SQL_EOF;
        }
    }

    public function __toString()
    {
        return $this->_prepareSql();
    }

    protected function _load()
    {
        $sql = $this->_prepareSql();

        $database = $this->getReadConnection();
        $statement = $database->prepare($sql);

        $args = func_get_args();
        $statement->execute(array_shift($args));

        $this->_items = array();
        if (func_num_args() > 1) {
            $reflection = new ReflectionClass(array_shift($args));
        } else {
            $reflection = new ReflectionClass($this->getEntityClassName());
        }

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $params = array_merge(array($row), $args);
            $this->_items[] = $reflection->newInstanceArgs($params);
        }

        return $this;
    }

    protected function _save()
    {
        foreach ($this->_items as $child) {
            $child->save();
        }

        return $this;
    }

    protected function _delete()
    {
        foreach ($this->_items as $child) {
            $child->delete();
        }

        return $this;
    }

    public function getSize()
    {
        $clone = clone $this;
        $clone->_fields = array();
        $clone->column('1');

        $sql = $clone->_prepareSql();

        $database = Wootook_Database::getSingleton();
        $statement = $database->prepare($sql);

        $args = func_get_args();
        $statement->execute(array_shift($args));
        $count = $statement->rowCount();
        $statement->closeCursor();

        return $count;
    }

    public function count()
    {
        return count($this->_items);
    }

    public function current()
    {
        return current($this->_items);
    }

    public function next()
    {
        next($this->_items);

        return $this;
    }

    public function rewind()
    {
        reset($this->_items);

        return $this;
    }

    public function key()
    {
        return key($this->_items);
    }

    public function valid()
    {
        return current($this->_items) !== false;
    }

    public function getFirstItem()
    {
        if (!$this->_)
        if (count($this->_items) > 0) {
            return $this->_items[0];
        }
        return null;
    }

    public function getLastItem()
    {
        $size = count($this->_items);
        if ($size > 0) {
            return $this->_items[$size - 1];
        }
        return null;
    }
}