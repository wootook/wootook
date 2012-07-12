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

namespace Wootook\Core\Database\Orm;

use Wootook\Core\Config,
    Wootook\Core\Database,
    Wootook\Core\Mvc\Model,
    Wootook\Core\PluginLoader,
    Wootook\Core\Profiler;

class DataMapper
    extends PluginLoader\PluginLoader
{
    protected $_rules = array();

    protected $_alias = array();

    public function __construct($config = array())
    {
        $this->registerNamespace('Wootook\\Core\\Database\\Orm\\DataMapper');

        if (!is_array($config) || !$config instanceof Config\Node) {
            return;
        }

        foreach ($config as $field => $typeConfig) {
            if (is_string($typeConfig)) {
                $this->addRule($field, $typeConfig);
            } else if (is_array($typeConfig) && isset($typeConfig['type'])) {
                if (isset($typeConfig['alias'])) {
                    $this->addRule($field, $typeConfig['type'], $typeConfig['alias']);
                } else {
                    $this->addRule($field, $typeConfig['type']);
                }
            }
        }
    }

    public function addRule($field, $type, $alias = null)
    {
        if (($instance = $this->load($type)) !== null) {
            $this->_rules[$field] = $instance;
        }

        if ($alias === null) {
            $alias = $field;
        }

        $this->_alias[$field] = $alias;

        return $this;
    }

    public function removeRule($field)
    {
        unset($this->_rules[$field]);
        unset($this->_alias[$field]);

        return $this;
    }

    protected function _load($className, $useSingleton, Array $constructorParams = array())
    {
        $reflection = new \ReflectionClass($className);
        if ($useSingleton && $reflection->implementsInterface('Wootook\\Core\\Base\\Singleton')) {
            $method = $reflection->getMethod('getSingleton');
            return $method->invoke(null);
        }

        try {
            return $reflection->newInstance($this);
        } catch (\ReflectionException $e) {
            Profiler\ErrorProfiler::getSingleton()
                ->addException($e);
            return null;
        }
    }

    /**
     *
     * @param Database\Resource $entity
     * @param Array $datas
     * @return Array
     */
    public function encode(Model\Entity $entity, Array $datas = array())
    {
        $aliasTable = array_flip($this->_alias);
        foreach ($entity->getAllDatas() as $aliasedField => $decodedValue) {
            if (!isset($aliasTable[$aliasedField])) {
                $field = $aliasedField;
            } else {
                $field = $aliasTable[$aliasedField];
            }

            if (isset($this->_rules[$field])) {
                $datas[$field] = $this->_rules[$field]->encode($decodedValue);
            } else {
                $datas[$field] = $decodedValue;
            }
        }

        return $datas;
    }

    /**
     *
     * @param Database\Resource $entity
     * @param Array $datas
     * @return Database\Resource
     */
    public function decode(Model\Entity $entity, Array $datas = array())
    {
        foreach ($datas as $field => $encodedValue) {
            if (isset($this->_rules[$field])) {
                $entity->setData($this->_alias[$field], $this->_rules[$field]->decode($encodedValue));
            } else {
                $entity->setData($field, $encodedValue);
            }
        }

        return $entity;
    }
}
