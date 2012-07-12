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

namespace Wootook\Core\Database\Sql;

use Wootook\Core\Database\Sql\Placeholder;

class Insert
    extends DmlQuery
{
    const SET    = 'SET';
    const INTO   = 'INTO';
    const SELECT = 'SELECT';

    protected function _init($tableName = null)
    {
        parent::_init($tableName);

        if ($tableName !== null) {
            $this->into($tableName);
        }

        return $this;
    }

    public function reset($part = null)
    {
        if ($part === null) {
            $this->_parts = array(
                self::INTO   => array(),
                self::SET    => array(),
                self::SELECT => array(),
                );
        } else if (isset($this->_parts[$part])) {
            $this->_parts[$part] = array();
        }

        return $this;
    }

    public function set($column, $value = null)
    {
        if (!is_array($column)) {
            $column = array($column => $value);
        }

        foreach ($column as $field => $value) {
            if ($value instanceof Placeholder\Placeholder) {
                $this->_placeholders[] = $value;
            }

            $this->_parts[self::SET][] = array(
                'value' => $value,
                'field' => $field
                );
        }

        return $this;
    }

    public function into($table, $schema = null)
    {
        $this->_parts[self::INTO] = array(
            'table'  => $table,
            'schema' => $schema,
            );

        return $this;
    }

    public function select()
    {
        if (!isset($this->_parts[self::SELECT])) {
            $this->_parts[self::SELECT] = $this->getConnection()->select();
        }

        return $this->_parts[self::SELECT];
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
            return $this->renderSet();
            break;
        case self::INTO:
            return $this->renderInto();
            break;
        case self::SELECT:
            return $this->renderSelect();
            break;
        }

        return null;
    }

    public function renderSet()
    {
        $fields = array();
        foreach ($this->_parts[self::SET] as $field) {
            if ($field['value'] instanceof Placeholder\Placeholder) {
                $fields[] = "{$this->_connection->quoteIdentifier($field['field'])}={$field['value']->toString()}";
            } else {
                $fields[] = "{$this->_connection->quoteIdentifier($field['field'])}={$this->_connection->quote($field['value'])}";
            }
        }

        if (!empty($fields)) {
            return "SET " . implode(", ", $fields);
        }
    }

    public function renderInto()
    {
        if ($this->_parts[self::INTO]['schema'] !== null) {
           $output = "{$this->_connection->quoteIdentifier($this->_parts[self::INTO]['schema'])}.{$this->_connection->quoteIdentifier($this->_parts[self::INTO]['table'])}";
        } else {
           $output = "{$this->_connection->quoteIdentifier($this->_parts[self::INTO]['table'])}";
        }

        return "INSERT INTO " . $output;
    }

    public function renderSelect()
    {
        if (isset($this->_parts[self::SELECT])) {
            return ' ' . $this->_parts[self::SELECT]->render();
        }
        return '';
    }

    public function render()
    {
        if (empty($this->_parts[self::SELECT])) {
            return implode("\n", array(
                $this->renderInto(),
                $this->renderSet(),
                ));
        } else {
            return implode("\n", array(
                $this->renderInto(),
                $this->renderSelect(),
                ));
        }
    }
}
