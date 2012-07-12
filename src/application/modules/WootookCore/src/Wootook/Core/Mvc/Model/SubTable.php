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
    Wootook\Core\Exception as CoreException;
/**
 *
 * @uses Wootook\Core\BaseObject
 */
abstract class SubTable
    extends Core\Base\BaseObject
{
    private $_isLoaded = false;
    protected $_idFieldNames = array();

    protected $_eventPrefix = 'entity.sub-table';

    protected function _save()
    {
        if ($this->_isLoaded !== false) {
            $fields = array();
            $values = array();
            $idFields = array();
            foreach ($this->getAllDatas() as $field => $value) {
                if (in_array($field, $this->_idFieldNames)) {
                    $idFields[] = "{$field}=:{$field}";
                } else {
                    $fields[] = "{$field}=:{$field}";
                }
                $values[$field] = $value;
            }
            $idFields = '(' . implode(') AND (', $idFields) . ')';
            $fields = implode(', ', $fields);

            $database = $this->getWriteConnection();
            $sql =<<<SQL_EOF
UPDATE {$database->getTable(self::getTableName())}
    SET {$fields}
    WHERE {$idFields}
SQL_EOF;
            $statement = $database->prepare($sql);

            $statement->execute($values);
        } else {
            $datas = $this->getAllDatas();
            $fields = implode(', ', array_keys($datas));

            $tokens = array();
            foreach ($datas as $field => $value) {
                $tokens[] = ":{$field}";
            }
            $tokens = implode(', ', $tokens);

            $database = $this->getWriteConnection();
            $sql =<<<SQL_EOF
INSERT INTO {$database->getTable(self::getTableName())} ($fields)
    VALUES ({$tokens})
SQL_EOF;
            $statement = $database->prepare($sql);
            $statement->execute($datas);
        }

        return $this;
    }

    protected function _load()
    {
        $idValues = func_get_arg(0);
        $database = $this->getReadConnection();

        $select = $database->select()
            ->from(array('main_table' => $database->getTable($this->getTableName())))
            ->limit(1);

        $idFields = array();
        foreach ($this->_idFieldNames as $field) {
            $select->where("{$database->quoteIdentifier($field)}=:{$field}");
        }

        $statement = $database->prepare($select);
        $statement->execute($idValues);

        $datas = $statement->fetch(PDO::FETCH_ASSOC);
        if (!is_array($datas) || empty($datas)) {
            throw new CoreException\DataAccessException('Could not load data: this id combination could not be found.');
        }

        $this->_data = array();
        $this->getDataMapper()->decode($this, $datas);

        $this->_isLoaded = true;

        return $this;
    }

    protected function _delete()
    {
        $database = $this->getWriteConnection();

        $idFields = array();
        $idValues = array();
        foreach ($this->_idFieldNames as $field) {
            $idFields[] = "{$field}=:{$field}";
            $idValues[$field] = $this->getData($field);
        }
        $idFields = '(' . implode(') AND (', $idFields) . ')';

        $sql =<<<SQL_EOF
DELETE {$database->getTable(self::getTableName())}
    WHERE $idFields
    LIMIT 1
SQL_EOF;
        $statement = $database->prepare($sql);
        $statement->execute($idValues);

        $this->_data = array();
        $this->_isLoaded = false;

        return $this;
    }
}
