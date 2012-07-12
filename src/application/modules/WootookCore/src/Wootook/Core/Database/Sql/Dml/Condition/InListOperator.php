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


class InListOperator
    extends Condition
{
    protected $_operator = null;
    protected $_field = null;
    protected $_values = array();

    public function __construct(Dml\Dml $query, $operator)
    {
        $this->_query = $query;
        $this->_operator = $operator;
    }

    protected function _render($rawOperator)
    {
        $output = array();
        foreach ($this->_values as $value) {
            if ($this->_value instanceof Placeholder\Placeholder) {
                $output[] = $value;
            }

            $output[] = $this->getQuery()->quote($value);
        }

        return $rawOperator . '(' . implode(', ', $output) . ')';
    }

    public function render()
    {
        switch ($this->_operator) {
            case Dml\Section\WhereAware::OPERATOR_IN:
                return $this->_render('=');

            case Dml\Section\WhereAware::OPERATOR_NOT_IN:
                return $this->_render('!=');
        }

        return '';
    }

    public function reset()
    {
        $this->_operator = null;
        $this->_field = null;
        $this->_values = array();

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

    public function addValue($value)
    {
        if ($value instanceof Placeholder\Placeholder) {
            $this->_addPlaceholder($value);
        }

        $this->_values[] = $value;

        return $this;
    }

    public function getAllValues()
    {
        return $this->_values;
    }
}
