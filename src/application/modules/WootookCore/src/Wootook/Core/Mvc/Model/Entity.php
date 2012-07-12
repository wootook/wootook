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

namespace Wootook\Core\Mvc\Model;

use Wootook\Core,
    Wootook\Core\Database,
    Wootook\Core\Database\Sql,
    Wootook\Core\Exception as CoreException;

abstract class Entity
    extends Core\Base\BaseObject
    implements EntityInterface
{
    protected $_tableName = null;

    protected $_idFieldName = null;

    public function setIdFieldName($fieldName)
    {
        $this->_idFieldName = $fieldName;

        return $this;
    }

    public function getIdFieldName()
    {
        return $this->_idFieldName;
    }

    public function setId($id)
    {
        $this->setData($this->getIdFieldName(), $id);

        return $this;
    }

    public function getId()
    {
        return $this->getData($this->getIdFieldName());
    }

    protected function _load()
    {
        $id = func_get_arg(0);

        $idFieldName = null;
        if (func_num_args() >= 2) {
            $idFieldName = func_get_arg(1);
        }
        if ($idFieldName === null) {
            $idFieldName = $this->getIdFieldName();
        }

        $database = $this->getReadConnection();
        if ($database === null) {
            throw new CoreException\DataAccessException('Could not load data: no read connection configured.');
        }

        $select = $database->select()
            ->from(array('main_table' => $database->getTable($this->getTableName())))
            ->where($idFieldName, new Sql\Placeholder\Param('id', $id))
            ->limit(1);

        $statement = $select->prepare();
        $statement->execute(array(
            'id' => $id
            ));

        $datas = $statement->fetch(Database\ConnectionManager::FETCH_ASSOC);
        if (!is_array($datas) || empty($datas)) {
            throw new CoreException\DataAccessException('Could not load data: this id could not be found.');
        }
        $realId = $datas[$this->getIdFieldName()];
        unset($datas[$this->getIdFieldName()]);

        $this->_data = array();
        $this->getDataMapper()->decode($this, $datas);
        $this->setId($realId);

        return $this;
    }

    protected function _save()
    {
        $adapter = $this->getWriteConnection();

        if ($adapter === null) {
            throw new CoreException\DataAccessException('Could not save data: no write connection configured.');
        }

        if ($this->getId() !== null) {
            $update = $adapter->update()
                ->into($adapter->getTable($this->getTableName()))
                ->where(new Sql\Placeholder\Expression("{$adapter->quoteIdentifier($this->getIdFieldName())}=:id", array('id' => $this->getId())));

            foreach ($this->getDataMapper()->encode($this, $this->getChangedDatas()) as $field => $value) {
                $update->set($field, new Sql\Placeholder\Param($field, $value));
            }
            try {
                $statement = $update->prepare();
                $statement->execute();
            } catch (CoreException\Database\AdapterError $e) {
                throw new CoreException\DataAccessException('Could not save data: ' . $e->getMessage(), null, $e);
            } catch (CoreException\Database\StatementError $e) {
                throw new CoreException\DataAccessException('Could not save data: ' . $e->getMessage(), null, $e);
            }
        } else {
            $insert = $adapter->insert()
                ->into($adapter->getTable($this->getTableName()));

            foreach ($this->getDataMapper()->encode($this, $this->getAllDatas()) as $field => $value) {
                $insert->set($field, new Sql\Placeholder\Param($field, $value));
            }
            try {
                $statement = $insert->prepare();
                $statement->execute();

                $id = $adapter->lastInsertId($adapter->getTable($this->getTableName()));
                $this->setId($id);
            } catch (CoreException\Database\AdapterError $e) {
                throw new CoreException\DataAccessException('Could not save data: ' . $e->getMessage(), null, $e);
            } catch (CoreException\Database\StatementError $e) {
                throw new CoreException\DataAccessException('Could not save data: ' . $e->getMessage(), null, $e);
            }
        }

        return $this;
    }

    protected function _delete()
    {
        $adapter = $this->getWriteConnection();
        if ($adapter === null) {
            throw new CoreException\DataAccessException('Could not delete data: no write connection configured.');
        }
        $delete = $adapter
            ->delete()
            ->from($adapter->getTable($this->getTableName()))
            ->where($this->getIdFieldName(), $this->getId())
            ->limit(1);

        try {
            $statement = $adapter->prepare($delete);
            $statement->execute();
        } catch (CoreException\Database\AdapterError $e) {
            throw new CoreException\DataAccessException('Could not delete data: ' . $e->getMessage(), null, $e);
        } catch (CoreException\Database\StatementError $e) {
            throw new CoreException\DataAccessException('Could not delete data: ' . $e->getMessage(), null, $e);
        }

        return $this;
    }

    public function setTableName($tableName)
    {
        $this->_tableName = $tableName;

        return $this;
    }

    public function getTableName()
    {
        return $this->_tableName;
    }
}
