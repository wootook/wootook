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
    Wootook\Core\Event,
    Wootook\Core\Exception as CoreException;

abstract class Statement
    implements \Iterator
{
    use Event\EventTarget;

    const EVENT_INIT      = 'init';
    const EVENT_READY     = 'ready';
    const EVENT_ERROR     = 'error';
    const EVENT_FETCH     = 'fetch';
    const EVENT_FETCH_ALL = 'fetch-all';
    const EVENT_FETCH_COL = 'fetch-column';

    protected $_adapter = null;

    protected $_currentIndex = 0;
    protected $_currentRow = null;

    protected $_asynchronousCallbacks = array();

    public function __construct(Adapter\Adapter $adapter, $sql)
    {
        $this->_adapter = $adapter;

        $this->_init($sql);
    }

    abstract protected function _init($sql);

    /**
     *
     * @param string|int $column
     * @param mixed $param
     * @param int $type
     * @return Statement
     */
    abstract public function bindColumn($column, &$param, $type = null);

    /**
     *
     * @param string|int $parameter
     * @param mixed $variable
     * @param int $type
     * @param int $length
     * @param unknown_type $options
     * @return Statement
     */
    abstract public function bindParam($parameter, &$variable, $type = null, $length = null, $options = null);

    /**
     *
     * @param string|int $parameter
     * @param mixed $value
     * @param int $type
     * @return Statement
     */
    abstract public function bindValue($parameter, $value, $type = null);

    /**
     * @abstract
     * @param array|null $params
     * @param bool $synchronous
     * @return bool
     */
    abstract public function execute(Array $params = null, $synchronous = false);

    /**
     * @abstract
     * @param null $style
     * @param int $orientation
     * @param int $cursorOffset
     * @return mixed
     */
    abstract public function fetch($style = null, $orientation = Database\ConnectionManager::FETCH_ORI_NEXT, $cursorOffset = 0);

    /**
     * @param int $style
     * @param int $col
     * @return mixed
     */
    abstract public function fetchAll($style = null, $col = null);

    /**
     * @param int $col
     * @return mixed
     */
    abstract public function fetchColumn($col = 0);

    /**
     *
     * @param string $class
     * @param array $config
     * @return \Wootook\Core\Base\BaseObject
     */
    abstract public function fetchObject($class = 'Wootook\\Core\\Base\\BaseObject', Array $constructorArgs = array());

    /**
     *
     * @param string $class
     * @param array $config
     * @return \Wootook\Core\Mvc\Model\Entity
     */
    public function fetchEntity($class = 'Wootook\\Core\\Mvc\\Model\\Entity', Array $constructorArgs = array())
    {
        $reflection = new ReflectionClass($class);
        $object = $reflection->newInstanceArgs($constructorArgs);

        if (!$object instanceof Wootook\Core\Mvc\Model\Entity) {
            throw new CoreException\Database\StatementError($this, 'Destination object should be a Wootook\\Core\\Mvc\\Model\\Entity instance.');
        }

        $data = $this->fetch(Database\ConnectionManager::FETCH_ASSOC);
        $object->getDataMapper()->decode($object, $data);

        return $object;
    }

    /**
     *
     * @return \Wootook\Core\Database\Adapter\Adapter
     */
    abstract public function getAdapter();

    /**
     * @param mixed $key
     */
    abstract public function getAttribute($key);

    /**
     * @param string $key
     * @param mixed $value
     * @return Statement
     */
    abstract public function setAttribute($key, $value);

    /**
     * @param int $mode
     * @return Statement
     */
    abstract public function setFetchMode($mode);

    /**
     * @return int
     */
    abstract public function columnCount();

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

    /**
     * @return int
     */
    abstract public function rowCount();

    /**
     * @return bool
     */
    abstract public function closeCursor();

    /**
     * @return bool
     */
    abstract public function nextRowset();

    /**
     * @param mixed $value
     * @return int|null
     */
    public function getParamType($value)
    {
        if (is_numeric($value)) {
            return Database\ConnectionManager::PARAM_INT;
        } else if (is_bool($value)) {
            return Database\ConnectionManager::PARAM_BOOL;
        } else if (is_string($value)) {
            return Database\ConnectionManager::PARAM_STR;
        }

        return null;
    }

    /**
     * @return null|mixed
     */
    public function current()
    {
        return $this->_currentRow;
    }

    /**
     * @return void
     */
    public function next()
    {
        $this->_currentRow = $this->fetch();
        $this->_currentIndex++;
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->_currentIndex;
    }

    /**
     * @return int
     */
    public function rewind()
    {
        return $this->_currentIndex;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return $this->_currentIndex < $this->rowCount() && $this->_currentIndex >= 0;
    }
}
