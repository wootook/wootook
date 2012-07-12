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

namespace Wootook\Core\Database\Adapter;

use Wootook\Core,
    Wootook\Core\Database\Orm,
    Wootook\Core\Database\Sql,
    Wootook\Core\Database\Statement,
    Wootook\Core\Event,
    Wootook\Core\Exception as CoreException;

abstract class Adapter
{
    use Event\EventTarget;

    protected $_handler = null;

    protected $_tablePrefix = null;

    protected $_statementClass = null;

    public function setStatementClass($statementClass)
    {
        $this->_statementClass = $statementClass;

        return $this;
    }

    public function getStatementClass()
    {
        return $this->_statementClass;
    }

    public function getDriverHandler()
    {
        return $this->_handler;
    }

    public function getDataMapper()
    {
        return new Orm\DataMapper();
    }

    /**
     *
     * @param string $prefix
     * @return Adapter
     */
    public function setTablePrefix($prefix)
    {
        $this->_tablePrefix = $prefix;

        return $this;
    }

    /**
     * @return string
     */
    public function getTablePrefix()
    {
        return $this->_tablePrefix;
    }

    /**
     * @return string
     */
    public function getTable($table)
    {
        return $this->getTablePrefix() . $table;
    }

    /**
     * @return Sql\Select
     */
    public function select()
    {
        return new Sql\Select($this);
    }

    /**
     * @return Sql\Insert
     */
    public function insert()
    {
        return new Sql\Insert($this);
    }

    /**
     * @return Sql\Update
     */
    public function update()
    {
        return new Sql\Update($this);
    }

    /**
     * @return Sql\Delete
     */
    public function delete()
    {
        return new Sql\Delete($this);
    }

    /**
     * @param string $data
     * @return string
     */
    abstract public function quote($data);

    /**
     * @param string $identifier
     * @return string
     */
    abstract public function quoteIdentifier($identifier);

    /**
     * @param string $identifier
     * @return string
     */
    public function quoteInto($string, $values)
    {
        $parts = preg_split('#(:[\w_]+|[?])#', $string, null, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        $result = '';
        if (is_array($values)) {
            $index = 0;
            foreach ($parts as $part) {
                if ($part == '?') {
                    $result .= $this->quote($values[$index++]);
                } else if (!empty($part) && $part[0] == ':') {
                    $key = substr($part, 1);

                    if (isset($values[$key])) {
                        $result .= $this->quote($values[$key]);
                    } else if (isset($values[$part])) {
                        $result .= $this->quote($values[$part]);
                    } else {
                        $result .= $part;
                    }
                } else {
                    $result .= $part;
                }
            }
        } else {
            foreach ($parts as $part) {
                if ($part == '?' || $part[0] == ':') {
                    $result .= $this->quote($values);
                } else {
                    $result .= $part;
                }
            }
        }

        return $result;
    }

    /**
     * @param $sql
     * @param array|null $params
     * @param Closure|null $asynchronousCallback
     * @return \Wootook\Core\Database\Statement\Statement
     * @throws \Wootook\Core\Exception\Database\StatementError
     */
    public function query($sql, Array $params = null, Closure $asynchronousCallback = null)
    {
        $statement = $this->prepare($sql, $params);

        if ($asynchronousCallback !== null) {
            $statement->registerEventListener(Statement\Statement::EVENT_READY, $asynchronousCallback);
        }
        if (!$statement->execute(null, $asynchronousCallback !== null)) {
            $message = sprintf('[SQLSTATE %s] Could not execute query: %s', $statement->errorState(), $statement->errorMessage());
            throw new CoreException\Database\StatementError($statement, $message);
        }

        return $statement;
    }

    /**
     * @param $sql
     * @param array|null $params
     * @param Closure|null $asynchronousCallback
     * @return bool
     */
    public function execute($sql, Array $params = null, Closure $asynchronousCallback = null)
    {
        $statement = $this->prepare($sql, $params);

        if ($asynchronousCallback !== null) {
            $statement->registerEventListener(Statement\Statement::EVENT_READY, $asynchronousCallback);
        }

        return $statement->execute(null, $asynchronousCallback !== null);
    }

    /**
     * @param $sql
     * @param array|null $params
     * @return \Wootook\Core\Database\Statement\Statement
     */
    public function prepare($sql, Array $params = null)
    {
        $statement = new $this->_statementClass($this, $sql);

        if ($params !== null) {
            foreach ($params as $paramKey => $paramValue) {
                $statement->bindValue($paramKey, $paramValue, $statement->getParamType($paramValue));
            }
        }

        return $statement;
    }

    /**
     * @return bool
     */
    abstract public function beginTransaction();

    /**
     * @return bool
     */
    abstract public function commit();

    /**
     * @return bool
     */
    abstract public function rollback();

    /**
     * @return bool
     */
    abstract public function lastInsertId();

    /**
     * @return string
     */
    abstract public function errorCode();

    /**
     * @return string
     */
    abstract public function errorMessage();

    /**
     * @return array
     */
    abstract public function errorInfo();

    /**
     * @return string
     */
    abstract public function errorState();
}
