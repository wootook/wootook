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

namespace Wootook\Core\Database\Statement;

use Wootook\Core\Database,
    Wootook\Core\Database\Adapter,
    Wootook\Core\Database\Statement,
    Wootook\Core\Database\Sql,
    Wootook\Core\Exception as CoreException;

class Pdo
    extends Statement
{
    /**
     * @var \PDOStatement
     */
    protected $_handler = null;

    /**
     * @var string|Sql\Dml
     */
    protected $_query = null;

    protected function _init($query)
    {
        $this->_query = $query;

        if ($this->_query instanceof Sql\Dml) {
            $this->_query->beforePrepare($this);
        }

        try {
            $this->_handler = $this->_adapter->getDriverHandler()->prepare($this->_query);
        } catch (\PDOException $e) {
            throw new CoreException\Database\StatementError($this, $e->getMessage(), null, $e);
        }

        if ($this->_query instanceof Sql\Dml) {
            $this->_query->afterPrepare($this);
        }

        $this->dispatchEvent(self::EVENT_INIT);

        return $this;
    }

    /**
     *
     * @param string|int $column
     * @param mixed $param
     * @param int $type
     * @return Statement\Statement
     */
    public function bindColumn($column, &$param, $type = null)
    {
        try {
            return $this->_handler->bindColumn($column, $param, $type);
        } catch (\PDOException $e) {
            throw new CoreException\Database\StatementError($this, $e->getMessage(), null, $e);
        }
    }

    /**
     *
     * @param string|int $parameter
     * @param mixed $variable
     * @param int $type
     * @param int $length
     * @param unknown_type $options
     * @return Statement\Statement
     */
    public function bindParam($parameter, &$variable, $type = null, $length = null, $options = null)
    {
        try {
            return $this->_handler->bindParam($parameter, $variable, $type, $length, $options);
        } catch (\PDOException $e) {
            throw new CoreException\Database\StatementError($this, $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     *
     * @param string|int $parameter
     * @param mixed $value
     * @param int $type
     * @return Statement\Statement
     */
    public function bindValue($parameter, $value, $type = null)
    {
        try {
            return $this->_handler->bindValue($parameter, $value, $type);
        } catch (\PDOException $e) {
            throw new CoreException\Database\StatementError($this, $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     *
     * @param array $params
     * @return bool
     */
    public function execute(Array $params = null, $asynchronous = false)
    {
        if ($this->_query instanceof Sql\Dml) {
            $this->_query->beforeExecute($this);
        }

        if ($asynchronous === true) {
            throw new CoreException\Database\StatementError($this, 'Asynchronous calls are not available with this driver.');
        }

        try {
            $result = $this->_handler->execute($params);
        } catch (\PDOException $e) {
            throw new CoreException\Database\StatementError($this, $e->getMessage(), $e->getCode(), $e);
        }

        if ($this->_query instanceof Sql\Dml) {
            $this->_query->afterExecute($this);
        }

        $this->dispatchEvent(self::EVENT_READY, array(
            'result' => $result
            ));

        return $result;
    }

    /**
     *
     * @param int $style
     * @param int $col
     * @return mixed
     */
    public function fetchAll($style = null, $col = null)
    {
        try {
            if ($style !== null) {
                if ($col !== null) {
                    $result = $this->_handler->fetchAll($style, $col);
                } else {
                    $result = $this->_handler->fetchAll($style);
                }
            } else {
                $result = $this->_handler->fetchAll($style);
            }
        } catch (\PDOException $e) {
            throw new CoreException\Database\StatementError($this, $e->getMessage(), $e->getCode(), $e);
        }

        $this->dispatchEvent(self::EVENT_FETCH_ALL, array(
            'result' => $result
            ));

        return $result;
    }

    /**
     *
     * @param int $col
     * @return mixed
     */
    public function fetchColumn($col = 0)
    {
        try {
            $result = $this->_handler->fetchColumn($col);
        } catch (\PDOException $e) {
            throw new CoreException\Database\StatementError($this, $e->getMessage(), $e->getCode(), $e);
        }

        $this->dispatchEvent(self::EVENT_FETCH_COL, array(
            'result' => $result
            ));

        return $result;
    }

    /**
     *
     * @return Database\Adapter\Adapter
     */
    public function getAdapter()
    {
        return $this->_adapter;
    }

    /**
     *
     * @param string $key
     */
    public function getAttribute($key)
    {
        try {
            return $this->_handler->getAttribute($key);
        } catch (\PDOException $e) {
            throw new CoreException\Database\StatementError($this, $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     *
     * @param string $key
     * @param mixed $value
     * @return Statement\Statement
     */
    public function setAttribute($key, $value)
    {
        try {
            return $this->_handler->setAttribute($key, $value);
        } catch (\PDOException $e) {
            throw new CoreException\Database\StatementError($this, $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param unknown_type $mode
     * @return Statement\Statement
     */
    public function setFetchMode($mode)
    {
        try {
            $params = func_get_args();
            return call_user_func_array(array($this->_handler, 'setFetchMode'), $params);
        } catch (\PDOException $e) {
            throw new CoreException\Database\StatementError($this, $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @return int
     */
    public function columnCount()
    {
        try {
            return $this->_handler->columnCount();
        } catch (\PDOException $e) {
            throw new CoreException\Database\StatementError($this, $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @return int
     */
    public function rowCount()
    {
        try {
            return $this->_handler->rowCount();
        } catch (\PDOException $e) {
            throw new CoreException\Database\StatementError($this, $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param int $col
     * @return mixed
     */
    public function fetch($style = null, $orientation = Database\ConnectionManager::FETCH_ORI_NEXT, $cursorOffset = 0)
    {
        try {
            $result = $this->_handler->fetch($style, $orientation, $cursorOffset);
        } catch (\PDOException $e) {
            throw new CoreException\Database\StatementError($this, $e->getMessage(), $e->getCode(), $e);
        }

        $this->dispatchEvent(self::EVENT_FETCH, array(
            'result'      => $result,
            'style'       => $style,
            'orientation' => $orientation,
            'offset'      => $cursorOffset
            ));

        return $result;
    }

    /**
     * @param string $class
     * @param array $config
     * @return Wootook\Core\BaseBaseObject
     */
    public function fetchObject($class = 'Wootook\\Core\\BaseBaseObject', Array $constructorArgs = array())
    {
        try {
            array_unshift($constructorArgs, $this->app());

            $result = $this->_handler->fetchObject($class, $constructorArgs);
        } catch (\PDOException $e) {
            throw new CoreException\Database\StatementError($this, $e->getMessage(), $e->getCode(), $e);
        }

        $this->dispatchEvent(self::EVENT_FETCH, array(
            'result'      => $result,
            'style'       => Database\ConnectionManager::FETCH_OBJ,
            'orientation' => Database\ConnectionManager::FETCH_ORI_NEXT,
            'offset'      => 0
            ));

        return $result;
    }

    /**
     * @return bool
     */
    public function closeCursor()
    {
        try {
            return $this->_handler->closeCursor();
        } catch (\PDOException $e) {
            throw new CoreException\Database\StatementError($this, $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @return bool
     */
    public function nextRowset()
    {
        try {
            return $this->_handler->nextRowset();
        } catch (\PDOException $e) {
            throw new CoreException\Database\StatementError($this, $e->getMessage(), $e->getCode(), $e);
        }
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
