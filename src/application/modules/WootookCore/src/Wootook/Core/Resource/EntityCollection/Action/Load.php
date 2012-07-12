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

namespace Wootook\Core\Resource\EntityCollection\Action;

use Wootook\Core\Database\Sql,
    Wootook\Core\Exception as CoreException;

/**
 *
 */
trait Load
{
    /** @var Sql\Select */
    protected $_select = null;

    public function getSelect()
    {
        if ($this->_select === null) {
            $this->_select = $this->getReadConnection()
                ->select()
                ->from(array('main_table' => $this->getReadConnection()->getTable($this->_entityTable)));

            $this->_prepareSelect($this->_select);
        }

        return $this->_select;
    }

    /**
     * @return Sql\Select
     */
    public function getSelectCount(Array $maintainGrouping)
    {
        $clone = clone $this->getSelect();
        $clone->reset(Sql\Select::COLUMNS);

        if (empty($maintainGrouping)) {
            $clone->reset(Sql\Select::GROUP);
        } else {
            $groupingFields = $clone->getPart(Sql\Select::GROUP);
            $clone->reset(Sql\Select::GROUP);
            foreach ($groupingFields as $field) {
                if (!in_array($field, $maintainGrouping)) {
                    continue;
                }
                $clone->group($field);
            }
        }

        $clone->column(new Sql\Placeholder\Expression('COUNT(*)'));
    }

    public function _prepareSelect(Sql\Select $select)
    {
    }

    public function _prepareSelectCount(Sql\Select $select)
    {
    }

    public function getSize(Array $maintainGrouping = array())
    {
        $select = $this->_getSelectCount($maintainGrouping);

        $database = $this->getReadConnection();
        try {
            $statement = $database->prepare($select);

            $statement->execute();
            $count = $statement->fetchColumn();
            $statement->closeCursor();
        } catch (\PDOException $e) {
            throw new CoreException\DataAccessException('Could not load data: ' . $e->getMessage(), 0, $e);
        }

        return $count;
    }

    public function load()
    {
        try {
            $this->_beforeLoad();
            $this->_load();
            $this->_afterLoad();

            $this->_setOriginalData($this->_data);
        } catch (\PDOException $e) {
            throw new CoreException\DataAccessException('Could not load data: ' . $e->getMessage(), 0, $e);
        }
        return $this;
    }

    abstract protected function _load();

    protected function _beforeLoad()
    {
        \Wootook::dispatchEvent('model.before-load', array('model' => $this));

        if ($this->_eventPrefix !== null && $this->_eventObject !== null) {
            \Wootook::dispatchEvent($this->_eventPrefix . '.before-load', array($this->_eventObject => $this));
        }

        return $this;
    }

    protected function _afterLoad()
    {
        \Wootook::dispatchEvent('model.after-load', array('model' => $this));

        if ($this->_eventPrefix !== null && $this->_eventObject !== null) {
            \Wootook::dispatchEvent($this->_eventPrefix . '.after-load', array($this->_eventObject => $this));
        }

        return $this;
    }
}
