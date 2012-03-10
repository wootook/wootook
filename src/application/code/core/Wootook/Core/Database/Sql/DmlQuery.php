<?php

abstract class Wootook_Core_Database_Sql_DmlQuery
    implements Wootook_Core_Database_Sql_Dml
{
    const WHERE   = 'WHERE';
    const LIMIT   = 'LIMIT';
    const OFFSET  = 'OFFSET';

    const OPERATOR_AND             = 'AND';
    const OPERATOR_OR              = 'OR';
    const OPERATOR_XOR             = 'XOR';
    const OPERATOR_EQUALS          = 'EQ';
    const OPERATOR_NOT_EQUALS      = 'NEQ';
    const OPERATOR_LOWER           = 'LT';
    const OPERATOR_GREATER         = 'GT';
    const OPERATOR_LOWER_EQUALS    = 'LTEQ';
    const OPERATOR_GREATER_EQUALS  = 'GTEQ';
    const OPERATOR_IS_NULL         = 'NULL';
    const OPERATOR_IN              = 'IN';
    const OPERATOR_NOT_IN          = 'NIN';
    const OPERATOR_FIND_IN_SET     = 'FINSET';
    const OPERATOR_NOT_FIND_IN_SET = 'NFINSET';
    const OPERATOR_DATE            = 'DATE';

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

    public function where($condition, $value = null)
    {
        if ($condition instanceof Wootook_Core_Database_Sql_Placeholder_Placeholder) {
            $this->_parts[self::WHERE][] = $condition;
        } else if (is_string($condition)) {
            if ($value === null) {
                $this->_parts[self::WHERE][] = $condition;
            } else {
                $adapter = $this->getReadConnection();
                $this->_parts[self::WHERE][] = $adapter->quoteInto($condition, $value);
            }
        } else if (is_array($condition)) {
            $where = $this->_translateSqlWhere($condition, 'or', $value);
            if ($where !== null) {
                $this->_parts[self::WHERE][] = $where;
            }
        }

        return $this;
    }

    public function limit($limit, $offset = null)
    {
        $this->_parts[self::LIMIT] = intval($limit);
        $this->_parts[self::OFFSET] = intval($offset);

        return $this;
    }

    public function renderWhere()
    {
        if (count($this->_parts[self::WHERE]) <= 0) {
            return '';
        }

        return "\nWHERE (" . implode(') AND (', $this->_parts[self::WHERE]) . ')';
    }

    public function renderLimit()
    {
        if (!$this->_parts[self::LIMIT]) {
            return '';
        }
        if (!$this->_parts[self::OFFSET]) {
            return sprintf("\nLIMIT %d", $this->_parts[self::LIMIT]);
        }
        return sprintf("\nLIMIT %d,%d", $this->_parts[self::LIMIT], $this->_parts[self::OFFSET]);
    }

    protected function _translateSqlWhere($field, $operator, $value)
    {
        if ($value === null) {
            return null;
        }
        $operator = strtoupper($operator);
        $adapter = $this->getReadConnection();

        $basicBinayOperatorList = array(
            self::OPERATOR_EQUALS         => '=',
            self::OPERATOR_NOT_EQUALS     => '!=',
            self::OPERATOR_LOWER          => '<',
            self::OPERATOR_GREATER        => '>',
            self::OPERATOR_LOWER_EQUALS   => '<=',
            self::OPERATOR_GREATER_EQUALS => '>='
            );

        if (in_array($operator, $basicBinayOperatorList)) {
            if ($value === true) {
                return "{$adapter->quoteIdentifier($field)}{$basicBinayOperatorList[$operator]}TRUE";
            } else if ($value === false) {
                return "{$adapter->quoteIdentifier($field)}{$basicBinayOperatorList[$operator]}FALSE";
            } else if (is_numeric($value)) {
                return sprintf("{$adapter->quoteIdentifier($field)}{$basicBinayOperatorList[$operator]}%d", $value);
            } else if ($value instanceof Wootook_Core_Database_Sql_Placeholder_Placeholder) {
                $this->_placeholders[] = $value;
                return sprintf("{$adapter->quoteIdentifier($field)}{$basicBinayOperatorList[$operator]}%s", $adapter->quote($value->toString()));
            } else {
                return sprintf("{$adapter->quoteIdentifier($field)}{$basicBinayOperatorList[$operator]}%s", $adapter->quote($value));
            }
        }

        switch ($operator) {
        case self::OPERATOR_IS_NULL:
            if ($value == false) {
                return "{$adapter->quoteIdentifier($field)} IS NOT NULL";
            } else {
                return "{$adapter->quoteIdentifier($field)} IS NULL";
            }
            break;

        case self::OPERATOR_AND:
        case self::OPERATOR_OR:
        case self::OPERATOR_XOR:
            $where = array();
            foreach ($value as $valueItem) {
                $subOperator = key($valueItem);
                $realValue = current($valueItem);

                if (is_array($realValue) && isset($realValue['field'])) {
                    if (!isset($realValue['value'])) {
                        continue;
                    }

                    $realField = $realValue['field'];
                    $realValue = $realValue['value'];
                } else {
                    $realField = $field;
                }

                if ($realValue === null) {
                    continue;
                }
                $actualValue = $this->_translateSqlWhere($realField, $subOperator, $realValue);
                if ($actualValue !== null) {
                    $where[] = $actualValue;
                }
            }

            if (count($where)) {
                return '((' . implode(') ' . strtoupper($operator) . ' (', $where) . '))';
            }
            break;

        case self::OPERATOR_IN:
            $valueList = array();
            foreach ($value as $setValue) {
                $valueList[] = $adapter->quote($setValue);
            }
            $implodedList = implode(',', $valueList);
            return "{$adapter->quoteIdentifier($field)} IN({$implodedList})";
            break;

        case self::OPERATOR_NOT_IN:
            $valueList = array();
            foreach ($value as $setValue) {
                $valueList[] = $adapter->quote($setValue);
            }
            $implodedList = implode(',', $valueList);
            return "{$adapter->quoteIdentifier($field)} NOT IN({$implodedList})";
            break;

        case self::OPERATOR_FIND_IN_SET:
            if (is_array($value)) {
                $subField = current($value);
                $subOperator = key($value);

                if (in_array($subOperator, $basicBinayOperatorList)) {
                    return "FIND_IN_SET({$adapter->quote($value)}, {$adapter->quoteIdentifier($field)}){$basicBinayOperatorList[$operator]}{$adapter->quoteIdentifier($subField)}";
                }
            } else {
                return "0 < FIND_IN_SET({$adapter->quote($value)}, {$adapter->quoteIdentifier($field)})";
            }
            break;

        case self::OPERATOR_NOT_FIND_IN_SET:
            return "0 = FIND_IN_SET({$adapter->quote($value)}, {$adapter->quoteIdentifier($field)})";
            break;

        case self::OPERATOR_DATE:
            $dateValues = array();
            if (isset($value['from'])) {
                if ($value['from'] instanceof Wootook_Core_DateTime) {
                    $dateValues['from'] = "{$adapter->quoteIdentifier($field)} >= {$adapter->quote($this->getDataMapper()->load('DateTime')->encode($value['from']))}";
                } else if (is_string($value['from'])) {
                    $dateValues['from'] = "{$adapter->quoteIdentifier($field)} >= {$adapter->quote($value['from'])}";
                } else if (is_numeric($value['from'])) {
                    $dateValues['from'] = "UNIX_TIMESTAMP({$adapter->quoteIdentifier($field)}) >= {$adapter->quote($value['from'])}";
                }
            }
            if (isset($value['to'])) {
                if ($value['to'] instanceof Wootook_Core_DateTime) {
                    $dateValues['to'] = "{$adapter->quoteIdentifier($field)} <= {$adapter->quote($this->getDataMapper()->load('DateTime')->encode($value['to']))}";
                } else if (is_string($value['to'])) {
                    $dateValues['to'] = "{$adapter->quoteIdentifier($field)} <= {$adapter->quote($value['to'])}";
                } else if (is_numeric($value['to'])) {
                    $dateValues['to'] = "UNIX_TIMESTAMP({$adapter->quoteIdentifier($field)}) <= {$adapter->quote($value['to'])}";
                }
            }

            return '(' . implode(' AND ', $dateValues) . ')';
            break;
        }

        return null;
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

    public function execute()
    {
        return $this->getConnection()->execute($this);
    }
}