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

use Wootook\Core\Database\Adapter,
    Wootook\Core\Database\Sql\Placeholder,
    Wootook\Core\Database\Statement;

abstract class DmlQuery
    implements Dml
{
    protected $_parts = array();

    protected $_connection = null;

    protected $_placeholders = array();

    public function __construct(Adapter\Adapter $connection, $tableName = null)
    {
        $this->setConnection($connection);

        $this->reset();
        $this->_init($tableName);
    }

    /**
     * @return \Wootook\Core\Database\Adapter\Adapter
     */
    public function getConnection()
    {
        return $this->_connection;
    }

    public function setConnection(Adapter\Adapter $connection)
    {
        $this->_connection = $connection;

        return $this;
    }

    protected function _init($param = null)
    {
        return $this;
    }

    public function setPart($part, $value)
    {
        $this->_parts[$part] = $value;

        return $this;
    }

    public function getPart($part)
    {
        if (isset($this->_parts[$part])) {
            return $this->_parts[$part];
        }

        return null;
    }

    public function getAllParts()
    {
        return $this->_parts;
    }

    public function quote($data)
    {
        return $this->getConnection()->quote($data);
    }

    public function quoteIdentifier($identifier)
    {
        return $this->getConnection()->quoteIdentifier($identifier);
    }

    public function quoteInto($identifier, $values)
    {
        return $this->getConnection()->quoteInto($identifier, $values);
    }

    public function addPlaceholder(Placeholder\Placeholder $placeholder)
    {
        $this->_placeholders[] = $placeholder;

        return $this;
    }

    public function beforePrepare(Statement\Statement $statement)
    {
        foreach ($this->_placeholders as $placeholder) {
            $placeholder->beforePrepare($statement);
        }

        return $this;
    }

    public function afterPrepare(Statement\Statement $statement)
    {
        foreach ($this->_placeholders as $placeholder) {
            $placeholder->afterPrepare($statement);
        }

        return $this;
    }

    public function beforeExecute(Statement\Statement $statement)
    {
        foreach ($this->_placeholders as $placeholder) {
            $placeholder->beforeExecute($statement);
        }

        return $this;
    }

    public function afterExecute(Statement\Statement $statement)
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

    public function execute(Array $params = null)
    {
        return $this->getConnection()->execute($this, $params);
    }
}
