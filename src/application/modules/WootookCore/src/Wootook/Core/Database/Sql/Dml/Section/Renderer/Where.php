<?php
/**
 * This file is part of Wootook
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.txt
 * @see http://wootook.org/
 *
 * Copyright (c) 2011-Present, Grégory PLANCHAT <g.planchat@gmail.com>
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing Wootook.
 *
 */

namespace Wootook\Core\Database\Sql\Dml\Section\Renderer;

use Wootook\Core,
    Wootook\Core\Database\Adapter,
    Wootook\Core\Database\Statement,
    Wootook\Core\Database\Sql\Placeholder;

trait Where
{
    const WHERE   = 'WHERE';

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

    public function where($condition, $value = null)
    {
        if ($condition instanceof Placeholder\Placeholder) {
            $this->_placeholders[] = $condition;
            $this->_parts[self::WHERE][] = $condition;
        } else if (is_array($value)) {
            $where = $this->_translateSqlWhere($condition, 'or', $value);
            if ($where !== null) {
                $this->_parts[self::WHERE][] = $where;
            }
        } else if ($value instanceof Placeholder\Placeholder) {
            $this->_parts[self::WHERE][] = "{$this->getConnection()->quoteIdentifier($condition)}=" . $value;
        } else {
            $this->_parts[self::WHERE][] = $this->getConnection()->quoteInto("{$this->getConnection()->quoteIdentifier($condition)}=?", $value);
        }

        return $this;
    }

    public function renderWhere()
    {
        if (count($this->_parts[self::WHERE]) <= 0) {
            return '';
        }

        return "    WHERE (" . implode(")\n      AND (", $this->_parts[self::WHERE]) . ')';
    }

    protected function _translateSqlWhere($field, $operator, $value)
    {
        if ($value === null) {
            return null;
        }
        $operator = strtoupper($operator);
        $adapter = $this->getConnection();

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
            } else if ($value instanceof Placeholder\Placeholder) {
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
                if ($value['from'] instanceof Core\DateTime) {
                    $dateValues['from'] = "{$adapter->quoteIdentifier($field)} >= {$adapter->quote($this->getConnection()->getDataMapper()->load('DateTime')->encode($value['from']))}";
                } else if (is_string($value['from'])) {
                    $dateValues['from'] = "{$adapter->quoteIdentifier($field)} >= {$adapter->quote($value['from'])}";
                } else if (is_numeric($value['from'])) {
                    $dateValues['from'] = "UNIX_TIMESTAMP({$adapter->quoteIdentifier($field)}) >= {$adapter->quote($value['from'])}";
                }
            }
            if (isset($value['to'])) {
                if ($value['to'] instanceof Core\DateTime) {
                    $dateValues['to'] = "{$adapter->quoteIdentifier($field)} <= {$adapter->quote($this->getConnection()->getDataMapper()->load('DateTime')->encode($value['to']))}";
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
}
