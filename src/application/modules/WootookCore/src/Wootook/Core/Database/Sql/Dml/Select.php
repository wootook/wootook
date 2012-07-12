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

use Wootook\Core\Database\Sql\Dml\Section,
    Wootook\Core\Database\Sql\Placeholder;

class Select
    extends DmlQuery
{
    use Section\Where, Section\Limit;

    const COLUMNS = 'COLUMNS';
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
        parent::_init($tableName);

        if ($tableName !== null) {
            $this->from($tableName);
        }

        return $this;
    }

    public function reset($part = null)
    {
        if ($part === null) {
            $this->_parts = array(
                self::COLUMNS => array(),
                self::FROM    => array(),
                self::JOIN    => array(),
                self::WHERE   => array(),
                self::UNION   => array(),
                self::LIMIT   => null,
                self::OFFSET  => null,
                self::GROUP   => array(),
                self::HAVING  => array(),
                self::ORDER   => array(),
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
            if ($column instanceof Placeholder\Placeholder) {
                $this->_placeholders[] = $column;
            }

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

    public function join($table, $condition, $fields = array('*'), $mode = self::JOIN_INNER, $schema = null)
    {
        $this->column($fields);
        if (is_array($table)) {
            $alias = key($table);
            $table = $this->_connection->getTable(current($table));

            if ($schema !== null) {
                $this->_parts[self::JOIN][] = "\n{$mode} JOIN {$this->_connection->quoteIdentifier($schema)}{$this->_connection->quoteIdentifier($table)} AS {$this->_connection->quoteIdentifier($alias)}"
                    . "\n  ON {$condition}";
            } else {
                $this->_parts[self::JOIN][] = "\n{$mode} JOIN {$this->_connection->quoteIdentifier($table)} AS {$this->_connection->quoteIdentifier($alias)}"
                    . "\n  ON {$condition}";
            }
        } else {
            $this->_parts[self::JOIN][] = "\n{$mode} JOIN {$this->_connection->quoteIdentifier($table)}"
                . "\n  ON {$condition}";
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

    public function renderColumns()
    {
        $fields = array();
        foreach ($this->_parts[self::COLUMNS] as $field) {
            if ($field['field'] instanceof Dml) {
                if ($field['alias'] !== null) {
                    $fields[] = "({$field['field']}) AS {$field['alias']}";
                } else {
                    $fields[] = "({$field['field']})";
                }
            } else if ($field['field'] instanceof Placeholder\Placeholder) {
                if ($field['alias'] !== null) {
                    $fields[] = "({$field['field']}) AS {$field['alias']}";
                } else {
                    $fields[] = "({$field['field']})";
                }
            } else if ($field['alias'] !== null) {
                if ($field['table'] !== null) {
                    $fields[] = "{$field['table']}.{$field['field']} AS {$field['alias']}";
                } else {
                    $fields[] = "{$field['field']} AS {$field['alias']}";
                }
            } else if ($field['table'] !== null) {
                $fields[] = "{$field['table']}.{$field['field']}";
            } else {
                $fields[] = "{$field['field']}";
            }
        }

        if (!empty($fields)) {
            return 'SELECT ' . implode(", ", $fields);
        }
        return 'SELECT *';
    }

    public function renderFrom()
    {
        $tables = array();
        foreach ($this->_parts[self::FROM] as $table) {
            if ($table['table'] instanceof Dml) {
                if ($table['alias'] !== null) {
                    $tables[] = "({$table['table']}) AS {$this->_connection->quoteIdentifier($table['alias'])}";
                } else {
                    $tables[] = "({$table['table']})";
                }
            } else if ($table['alias'] !== null) {
                if ($table['schema'] !== null) {
                    $tables[] = "{$this->_connection->quoteIdentifier($table['schema'])}.{$this->_connection->quoteIdentifier($table['table'])} AS {$this->_connection->quoteIdentifier($table['alias'])}";
                } else {
                    $tables[] = "{$this->_connection->quoteIdentifier($table['table'])} AS {$this->_connection->quoteIdentifier($table['alias'])}";
                }
            } else if ($table['schema'] !== null) {
                $tables[] = "{$this->_connection->quoteIdentifier($table['schema'])}.{$this->_connection->quoteIdentifier($table['table'])}";
            } else {
                $tables[] = "({$this->_connection->quoteIdentifier($table['table'])})";
            }
        }

        return "  FROM " . implode(', ', $tables);
    }

    public function renderJoin()
    {
        return implode('', $this->_parts[self::JOIN]);
    }

    public function renderOrder()
    {
        if (count($this->_parts[self::ORDER]) <= 0) {
            return null;
        }
        return "  ORDER BY " . implode(', ', $this->_parts[self::ORDER]);
    }

    public function renderUnion()
    {
        $statements = array();
        foreach ($this->_parts[self::UNION] as $statement) {
            $statements[] = $statement->render();
        }

        return "  (\n" . implode("  )\n  UNION\n  (\n", $statements) . "\n  )";
    }

    public function renderGroup()
    {
        if (count($this->_parts[self::GROUP]) <= 0) {
            return null;
        }
        return "  GROUP BY " . implode(', ', $this->_parts[self::GROUP]);
    }

    public function renderHaving()
    {
        if (count($this->_parts[self::HAVING]) <= 0) {
            return null;
        }
        return "  HAVING " . implode(', ', $this->_parts[self::HAVING]);
    }

    public function render()
    {
        if (empty($this->_parts[self::UNION])) {
            return implode("\n", array(
                $this->renderColumns(),
                $this->renderFrom(),
                $this->renderJoin(),
                $this->renderWhere(),
                $this->renderGroup(),
                $this->renderHaving(),
                $this->renderOrder(),
                $this->renderLimit()
                ));
        } else {
            return implode("\n", array(
                $this->renderUnion(),
                $this->renderWhere(),
                $this->renderOrder(),
                $this->renderLimit()
                ));
        }
    }
}
