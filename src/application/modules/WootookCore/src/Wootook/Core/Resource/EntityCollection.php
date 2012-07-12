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

namespace Wootook\Core\Resource;

use Wootook\Core\Database,
    Wootook\Core\Database\Adapter,
    Wootook\Core\Database\Orm,
    Wootook\Core\Database\Sql,
    Wootook\Core\Database\Sql\Placeholder,
    Wootook\Core\Exception as CoreException;

abstract class EntityCollection
    implements \Iterator, \Countable
{
    use Database\Resource\DataMapper,
        Database\Resource\ReadConnection;

    protected $_entityTable = null;
    protected $_entityClass = null;

    protected $_eventObject = null;
    protected $_eventPrefix = null;

    protected $_curPage = null;
    protected $_pageSize = null;

    protected $_items = array();

    public function __construct(Adapter\Adapter $connection = null)
    {
        $this->setReadConnection($connection);

        $this->_construct();
    }

    abstract protected function _construct();

    protected function _init($entityTable, $entityClass)
    {
        $this->_entityTable = $entityTable;
        $this->_entityClass = $entityClass;
    }

    public function load()
    {
        $this->_beforeLoad();
        $this->_load();
        $this->_afterLoad();

        return $this;
    }

    protected function _beforeLoad()
    {
         \Wootook::dispatchEvent('resource.collection.before-load', array('model' => $this));

        if ($this->_eventPrefix !== null && $this->_eventObject !== null) {
            \Wootook::dispatchEvent($this->_eventPrefix . '.before-load', array($this->_eventObject => $this));
        }

        return $this;
    }

    protected function _afterLoad()
    {
        \Wootook::dispatchEvent('resource.collection.after-load', array('model' => $this));

        if ($this->_eventPrefix !== null && $this->_eventObject !== null) {
            \Wootook::dispatchEvent($this->_eventPrefix . '.after-load', array($this->_eventObject => $this));
        }

        return $this;
    }

    protected function _load()
    {
        $select = clone $this->getSelect();

        if ($this->_pageSize !== null) {
            if ($this->_curPage !== null) {
                $select->limit($this->_pageSize, ($this->_curPage - 1) * $this->_pageSize);
            } else {
                $select->limit($this->_pageSize);
            }
        }

        $statement = $select->prepare();

        $args = func_get_args();
        $statement->execute(array_shift($args));

        $this->_items = array();
        if (func_num_args() > 1) {
            $reflection = new \ReflectionClass(array_shift($args));
        } else {
            $reflection = new \ReflectionClass($this->_entityClass);
        }

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $item = $reflection->newInstanceArgs();
            $item->getDataMapper()->decode($item, $row);

            $this->_items[] = $item;
        }

        return $this;
    }

    public function count()
    {
        return count($this->_items);
    }

    public function current()
    {
        return current($this->_items);
    }

    public function next()
    {
        next($this->_items);

        return $this;
    }

    public function rewind()
    {
        reset($this->_items);

        return $this;
    }

    public function key()
    {
        return key($this->_items);
    }

    public function valid()
    {
        return current($this->_items) !== false;
    }

    public function getFirstItem()
    {
        if (count($this->_items) > 0) {
            return $this->_items[0];
        }
        return null;
    }

    public function getLastItem()
    {
        $size = count($this->_items);
        if ($size > 0) {
            return $this->_items[$size - 1];
        }
        return null;
    }

    public function addOrderBy($field, $type = 'ASC')
    {
        $this->getSelect()->order($field, $type);

        return $this;
    }

    public function addFieldToFilter($field, $value = null)
    {
        $this->getSelect()->where($field, $value);

        return $this;
    }

    public function setPage($curPage, $pageSize)
    {
        $this->setCurPage($curPage)->setPageSize($pageSize);

        return $this;
    }

    public function setCurPage($curPage)
    {
        $this->_curPage = $curPage;

        return $this;
    }

    public function setPageSize($pageSize)
    {
        $this->_pageSize = $pageSize;

        return $this;
    }
}
