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

abstract class Pdo
    extends Adapter
{
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
            $this->_handler = new \PDO($this->_buildDsn($config), $config->username, $config->password, $options);
        } catch (\PDOException $e) {
            throw new CoreException\Database\AdapterError($this, $e->getMessage(), null, $e);
        }
    }

    /**
     * @abstract
     * @param \Wootook\Core\Config\Node $config
     * @return string
     */
    abstract protected function _buildDsn(Config\Node $config);

    public function quote($data)
    {
        try {
            return $this->_handler->quote($data);
        } catch (\PDOException $e) {
            throw new CoreException\Database\AdapterError($this, $e->getMessage(), null, $e);
        }
    }

    /**
     * @return Statement\Statement
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
    public function beginTransaction()
    {
        try {
            return $this->_handler->beginTransaction();
        } catch (\PDOException $e) {
            throw new CoreException\Database\AdapterError($this, $e->getMessage(), null, $e);
        }
        return false;
    }

    /**
     * @return bool
     */
    public function commit()
    {
        try {
            return $this->_handler->commit();
        } catch (\PDOException $e) {
            throw new CoreException\Database\AdapterError($this, $e->getMessage(), null, $e);
        }
        return false;
    }

    /**
     * @return bool
     */
    public function rollback()
    {
        try {
            return $this->_handler->rollback();
        } catch (\PDOException $e) {
            throw new CoreException\Database\AdapterError($this, $e->getMessage(), null, $e);
        }
        return false;
    }

    /**
     * @return int
     */
    public function lastInsertId()
    {
        try {
            return $this->_handler->lastInsertId();
        } catch (\PDOException $e) {
            throw new CoreException\Database\AdapterError($this, $e->getMessage(), null, $e);
        }
        return false;
    }

    /**
     * @return string
     */
    public function errorCode()
    {
        return $this->_handler->errorCode();
    }

    /**
     * @return array
     */
    public function errorInfo()
    {
        return $this->_handler->errorInfo();
    }

    /**
     * @return string
     */
    public function errorMessage()
    {
        $info = $this->_handler->errorInfo();

        return $info[2];
    }

    /**
     * @return string
     */
    public function errorState()
    {
        $info = $this->_handler->errorInfo();

        return $info[0];
    }
}
