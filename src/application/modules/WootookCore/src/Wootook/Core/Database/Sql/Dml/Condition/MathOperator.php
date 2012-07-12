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
    Wootook\Core\Database\Sql\Placeholder;


class MathOperator
    extends Condition
{
    protected $_operator = null;
    protected $_field = null;
    protected $_value = null;

    public function __construct(Dml\Dml $query, $field, $operator, $value)
    {
        $this->_query = $query;
        $this->_field = $field;
        $this->_operator = $operator;
        $this->_value = $value;
    }

    protected function _render($rawOperator)
    {
        if ($this->_value instanceof Placeholder\Placeholder) {
            return "{$this->getQuery()->quoteIdentifier($this->_field)}{$rawOperator}{$this->_value}";
        }

        return "{$this->getQuery()->quoteIdentifier($this->_field)}{$rawOperator}{$this->getQuery()->quote($this->_value)}";
    }

    public function render()
    {
        switch ($this->_operator) {
            case Dml\Section\WhereAware::OPERATOR_EQUALS:
                return $this->_render('=');

            case Dml\Section\WhereAware::OPERATOR_NOT_EQUALS:
                return $this->_render('!=');

            case Dml\Section\WhereAware::OPERATOR_LOWER:
                return $this->_render('<');

            case Dml\Section\WhereAware::OPERATOR_GREATER:
                return $this->_render('>');

            case Dml\Section\WhereAware::OPERATOR_LOWER_EQUALS:
                return $this->_render('<=');

            case Dml\Section\WhereAware::OPERATOR_GREATER_EQUALS:
                return $this->_render('>=');
        }

        return '';
    }

    public function reset()
    {
        $this->_operator = null;
        $this->_field = null;
        $this->_value = null;

        $this->_clearPlaceholders();

        return $this;
    }

    public function setOperator($operator)
    {
        $this->_operator = $operator;

        return $this;
    }

    public function getOperator()
    {
        return $this->_operator;
    }

    public function setField($field)
    {
        $this->_field = $field;

        return $this;
    }

    public function getField()
    {
        return $this->_field;
    }

    public function setValue($value)
    {
        if ($value instanceof Placeholder\Placeholder) {
            $this->_addPlaceholder($value);
        }

        $this->_value = $value;

        return $this;
    }

    public function getValue()
    {
        return $this->_value;
    }
}
