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

namespace Wootook\Core\Database\Sql\Dml\Condition;

use Wootook\Core\Database\Sql\Dml,
    Wootook\Core\Exception as CoreException;

class LogicalOperator
    extends Condition
    implements \ArrayAccess, \Countable, \Iterator
{
    /**
     * @var string|int
     */
    protected $_operator = null;

    /**
     * @var array
     */
    protected $_conditions = array();

    /**
     * @var array
     */
    protected $_allowedOperators = array();

    public function __construct(Dml\Dml $query, $operator = null, Array $allowedOperators = array())
    {
        $this->_query = $query;
        $this->_operator = $operator;

        $this->_allowedOperators = array(
            Dml\Section\WhereAware::OPERATOR_NOT => function(LogicalOperator $conditions) {
                return '(NOT (' . $conditions[0] . ')';
                },
            Dml\Section\WhereAware::OPERATOR_AND => function(LogicalOperator $conditions) {
                return '(' . implode(') AND (', $conditions->getAllConditions()) . ')';
                },
            Dml\Section\WhereAware::OPERATOR_NAND => function(LogicalOperator $conditions) {
                return '(NOT (' . implode(') AND (', $conditions->getAllConditions()) . '))';
                },
            Dml\Section\WhereAware::OPERATOR_OR => function(LogicalOperator $conditions) {
                return '(' . implode(') OR (', $conditions->getAllConditions()) . ')';
                },
            Dml\Section\WhereAware::OPERATOR_NOR => function(LogicalOperator $conditions) {
                return '(NOT (' . implode(') OR (', $conditions->getAllConditions()) . '))';
                },
            Dml\Section\WhereAware::OPERATOR_XOR => function(LogicalOperator $conditions) {
                return '(' . implode(') XOR (', $conditions->getAllConditions()) . ')';
                },
            Dml\Section\WhereAware::OPERATOR_NXOR => function(LogicalOperator $conditions) {
                return '(NOT (' . implode(') XOR (', $conditions->getAllConditions()) . '))';
                },
            );

        foreach ($allowedOperators as $operatorKey => $operator) {
            if (!$operator instanceof \Closure) {
                continue;
            }
            /** @var \Closure $operator */
            $this->addAllowedOperator($operatorKey, $operator);
        }
    }

    /**
     * @return LogicalOperator
     */
    public function reset()
    {
        $this->_operator = null;
        $this->_conditions = array();

        $this->_clearPlaceholders();

        return $this;
    }

    /**
     * @return string
     */
    public function render()
    {
        if (isset($this->_allowedOperators[$this->_operator])) {
            return $this->_allowedOperators[$this->_operator]($this);
        }

        return '';
    }

    /**
     * @param string|int $operator
     * @return LogicalOperator
     */
    public function setOperator($operator)
    {
        if (!isset($this->_allowedOperators[$operator])) {
            throw new CoreException\InvalidArgumentException('Inexistent operator');
        }
        $this->_operator = $operator;

        return $this;
    }

    /**
     * @return string|int
     */
    public function getOperator()
    {
        return $this->_operator;
    }

    /**
     * @param string|int $operator
     * @param Closure $renderer
     * @return LogicalOperator
     */
    public function addAllowedOperator($operator, \Closure $renderer)
    {
        $this->_allowedOperators[$operator] = $renderer;

        return $this;
    }

    /**
     * @param string|int $operator
     * @return LogicalOperator
     */
    public function removeAllowedOperator($operator)
    {
        $key = array_search($operator, $this->_allowedOperators);
        if ($key !== null) {
            unset($this->_allowedOperators[$key]);
        }

        return $this;
    }

    /**
     * @param string|int $operator
     * @param Closure $renderer
     * @return \Closure
     */
    public function getAllowedOperatorRenderer($operator)
    {
        if (!isset($this->_allowedOperators[$operator])) {
            throw new CoreException\InvalidArgumentException('Inexistent operator');
        }
        return $this->_allowedOperators[$operator];
    }

    /**
     * @return array
     */
    public function getAllAllowedOperators()
    {
        return $this->_allowedOperators;
    }

    /**
     * @param Condition $condition
     * @return LogicalOperator
     */
    public function addCondition(Condition $condition)
    {
        $this->_conditions[] = $condition;

        return $this;
    }

    /**
     * @return array
     */
    public function getAllConditions()
    {
        return $this->_conditions;
    }

    public function count()
    {
        return count($this->_conditions);
    }

    public function current()
    {
        return current($this->_conditions);
    }

    public function key()
    {
        return key($this->_conditions);
    }

    public function valid()
    {
        return key($this->_conditions) !== false;
    }

    public function next()
    {
        next($this->_conditions);
    }

    public function rewind()
    {
        reset($this->_conditions);
    }

    public function offsetExists($offset)
    {
        return isset($this->_conditions[$offset]);
    }

    public function offsetSet($offset, $value)
    {
        if (!is_int($offset)) {
            throw new CoreException\InvalidArgumentException('Offset should be an integer');
        }
        $this->_conditions[$offset] = $value;
    }

    public function offsetGet($offset)
    {
        if (!is_int($offset)) {
            throw new CoreException\InvalidArgumentException('Offset should be an integer');
        }
        return $this->_conditions[$offset];
    }

    public function offsetUnset($offset)
    {
        if (!is_int($offset)) {
            throw new CoreException\InvalidArgumentException('Offset should be an integer');
        }
        unset($this->_conditions[$offset]);
    }
}
