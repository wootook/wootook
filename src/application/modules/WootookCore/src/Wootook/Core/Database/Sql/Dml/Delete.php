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

namespace Wootook\Core\Database\Sql\Dml;

class Delete
    extends DmlQuery
{
    const FROM = 'FROM';

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
                self::FROM   => null,
                self::WHERE  => array(),
                self::OFFSET => null,
                self::LIMIT  => null,
                );
        } else if (isset($this->_parts[$part])) {
            $this->_parts[$part] = array();
        }

        return $this;
    }

    public function from($table, $schema = null)
    {
        $this->_parts[self::FROM] = array(
            'table'  => $table,
            'schema' => $schema
            );

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
        case self::FROM:
            return $this->renderFrom();
            break;
        case self::WHERE:
            return $this->renderWhere();
            break;
        case self::WHERE:
            return $this->renderWhere();
            break;
        case self::LIMIT:
            return $this->renderLimit();
            break;
        }

        return null;
    }

    public function renderFrom()
    {
        if ($this->_parts[self::FROM]['schema'] !== null) {
            return "DELETE FROM {$this->getConnection()->quoteIdentifier($this->_parts[self::FROM]['schema'])}.{$this->getConnection()->quoteIdentifier($this->_parts[self::FROM]['table'])}";
        }
        return "DELETE FROM {$this->getConnection()->quoteIdentifier($this->_parts[self::FROM]['table'])}";
    }

    public function render()
    {
        return implode("\n", array(
            $this->renderFrom(),
            $this->renderWhere(),
            $this->renderLimit(),
            ));
    }
}
