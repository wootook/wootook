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

use Wootook\Core\Config,
    Wootook\Core\Database,
    Wootook\Core\Database\Statement,
    Wootook\Core\Database\Sql,
    Wootook\Core\Exception as CoreException;

class Mysql
    extends Adapter
{
    protected $_statementClass = 'Wootook\\Core\\Database\\Statement\\Mysql';

    /**
     *
     * Enter description here ...
     * @param Config\Node $config
     * @param array $params
     */
    public function __construct(Config\Node $config, Array $options = array())
    {
        if (!isset($options[Database\ConnectionManager::ATTR_ERRMODE])) {
            $options[Database\ConnectionManager::ATTR_ERRMODE] = Database\ConnectionManager::ERRMODE_EXCEPTION;
        }

        try {
            $this->_handler = new \mysqli($config->hostname, $config->username, $config->password, $config->database, (int) $config->port, $options);
        } catch (\mysqli_sql_exception $e) {
            throw new CoreException\Database\AdapterError($this, $e->getMessage(), null, $e);
        }

        if ($this->_handler->connect_errno) {
            throw new CoreException\Database\AdapterError($this, $this->_handler->connect_error, $this->_handler->connect_errno);
        }
    }

    /**
     * (non-PHPdoc)
     * @see Adapter\Adapter::quoteIdentifier()
     */
    public function quoteIdentifier($identifier)
    {
        return "`$identifier`";
    }

    public function quote($data)
    {
        $result = $this->_handler->real_escape_string($data);

        if ($this->_handler->errno) {
            throw new CoreException\Database\AdapterError($this, $this->_handler->connect_error, $this->_handler->connect_errno);
        }

        return $result;
    }

    /**
     * @return bool
     */
    public function beginTransaction()
    {
        $result = $this->_handler->autocommit(false);

        if ($this->_handler->errno) {
            throw new CoreException\Database\AdapterError($this, $this->_handler->connect_error, $this->_handler->connect_errno);
        }

        return $result;
    }

    /**
     * @return bool
     */
    public function commit()
    {
        $result = $this->_handler->commit();
        $this->_handler->autocommit(true);

        if ($this->_handler->errno) {
            throw new CoreException\Database\AdapterError($this, $this->_handler->connect_error, $this->_handler->connect_errno);
        }

        return $result;
    }

    /**
     * @return bool
     */
    public function rollback()
    {
        $result = $this->_handler->rollback();
        $this->_handler->autocommit(true);

        if ($this->_handler->errno) {
            throw new CoreException\Database\AdapterError($this, $this->_handler->connect_error, $this->_handler->connect_errno);
        }

        return $result;
    }

    /**
     * @return int
     */
    public function lastInsertId()
    {
        return $this->_handler->insert_id;
    }

    /**
     * @return string
     */
    public function errorCode()
    {
        return $this->_handler->errno;
    }

    /**
     * @return array
     */
    public function errorInfo()
    {
        return array(
            $this->_handler->sqlstate,
            $this->_handler->errno,
            $this->_handler->error,
            );
    }

    /**
     * @return string
     */
    public function errorMessage()
    {
        $info = $this->_handler->error;

        return $info[2];
    }

    /**
     * @return string
     */
    public function errorState()
    {
        $info = $this->_handler->sqlstate;

        return $info[0];
    }
}
