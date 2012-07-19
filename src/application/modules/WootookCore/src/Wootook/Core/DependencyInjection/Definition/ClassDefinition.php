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

namespace Wootook\Core\DependencyInjection\Definition;

use Wootook\Core\Base\Service,
    Wootook\Core\Config,
    Wootook\Core\DependencyInjection,
    Wootook\Core\Exception as CoreException;

/**
 * Class definition for the DependencyInjection component, used to define a
 * class's dependencies.
 *
 * @package    WootookCore
 * @subpackage DependencyInjection
 * @author     Grégory PLANCHAT <g.planchat@gmail.com>
 * @see        http://wootook.org/
 */
class ClassDefinition
{
    /**
     * @var string
     */
    protected $_className = null;

    /**
     * @var \ReflectionClass
     */
    protected $_reflector = null;

    /**
     * @var array
     */
    protected $_methodDefinitions = array();

    /**
     * @var null|\Wootook\Core\DependencyInjection\Registry
     */
    protected $_registry = null;

    /**
     * @var string
     */
    protected $_methodDefinitionClassName = 'Wootook\\Core\\DependencyInjection\\Definition\\MethodDefinition';

    /**
     * @param string $className
     * @param null|string $methodDefinitionHandlerClass
     */
    public function __construct($className, DependencyInjection\Registry $registry = null, $methodDefinitionClassName = null)
    {
        $this->_className = $className;
        try {
            $this->_reflector = new \ReflectionClass($className);
        } catch (\ReflectionException $e) {
            throw new CoreException\DependencyInjection\BadMethodCallException($e->getMessage(), $e->getCode(), $e);
        }

        if ($methodDefinitionClassName !== null) {
            $this->setMethodDefinitionClassName($methodDefinitionClassName);
        }

        if ($registry !== null) {
            $this->setRegistry($registry);
        }
    }

    /**
     * @param \Wootook\Core\DependencyInjection\Registry $registry
     * @return ClassDefinition
     */
    public function setRegistry(DependencyInjection\Registry $registry)
    {
        $this->_registry = $registry;

        return $this;
    }

    /**
     * @return null|\Wootook\Core\DependencyInjection\Registry
     */
    public function getRegistry()
    {
        return $this->_registry;
    }

    /**
     * @param string $methodName
     * @return \Wootook\Core\DependencyInjection\Definition\MethodDefinition
     * @throws \Wootook\Core\Exception\DependencyInjection\InvalidArgumentException
     */
    public function getMethodDefinition($methodName, $registerInstanceIfNew = false)
    {
        if (!is_string($methodName)) {
            throw new CoreException\DependencyInjection\InvalidArgumentException('Method names only accept string.');
        }

        if (isset($this->_methodDefinitions[$methodName])) {
            return $this->_methodDefinitions[$methodName];
        }

        $definition = $this->initMethodDefinition($methodName);
        if ($registerInstanceIfNew === true) {
            $this->registerMethodDefinition($methodName, $definition);
        }
        return $definition;
    }

    /**
     * @param string $methodName
     * @return \Wootook\Core\DependencyInjection\Definition\MethodDefinition
     */
    public function initMethodDefinition($methodName)
    {
        if (!is_string($methodName)) {
            throw new CoreException\DependencyInjection\InvalidArgumentException('Method names only accept string.');
        }

        $class = $this->getMethodDefinitionClassName();

        return new $class($this, $methodName, $this->getRegistry());
    }

    /**
     * @param $methodName
     * @param MethodDefinition $methodDefinition
     * @return ClassDefinition
     * @throws \Wootook\Core\Exception\DependencyInjection\InvalidArgumentException
     */
    public function registerMethodDefinition($methodName, MethodDefinition $methodDefinition)
    {
        if (!is_string($methodName)) {
            throw new CoreException\DependencyInjection\InvalidArgumentException('Method names only accept string.');
        }

        $this->_methodDefinitions[$methodName] = $methodDefinition;

        return $this;
    }

    /**
     * @param $methodName
     * @return ClassDefinition
     */
    public function addMethodDefinition($methodName)
    {
        $this->registerMethodDefinition($methodName, $this->initMethodDefinition($methodName));

        return $this;
    }

    /**
     * @param $methodName
     * @param MethodDefinition $definition
     * @return ClassDefinition
     * @throws \Wootook\Core\Exception\DependencyInjection\InvalidArgumentException
     */
    public function setMethodDefinition($methodName, MethodDefinition $definition)
    {
        if (!is_string($methodName)) {
            throw new CoreException\DependencyInjection\InvalidArgumentException('Method names only accept string.');
        }

        $this->_methodDefinitions[$methodName] = $definition;

        return $this;
    }

    /**
     * @param $methodDefinitionClassName
     * @return string
     * @throws \Wootook\Core\Exception\DependencyInjection\InvalidArgumentException
     */
    public function setMethodDefinitionClassName($methodDefinitionClassName)
    {
        if (!is_string($methodDefinitionClassName)) {
            throw new CoreException\DependencyInjection\InvalidArgumentException('Class names only accept string.');
        }

        $this->_methodDefinitionClassName = $methodDefinitionClassName;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethodDefinitionClassName()
    {
        return $this->_methodDefinitionClassName;
    }

    /**
     * @return array
     */
    public function getAllMethodDefinitions()
    {
        return $this->_methodDefinitions;
    }

    /**
     * @param array $args
     * @return object
     * @throws \Wootook\Core\Exception\DependencyInjection\RuntimeException
     */
    public function newInstance(Array $args = array())
    {
        try {
            if ($this->getReflector()->hasMethod('__construct')) {
                $methodDefinition = $this->getMethodDefinition('__construct');

                return $this->getReflector()->newInstanceArgs($methodDefinition->compileArgs($args));
            } else {
                return $this->getReflector()->newInstance();
            }
        } catch (\ReflectionException $e) {
            throw new CoreException\DependencyInjection\RuntimeException('Could not instantiate class : constructor may be non-public.', null, $e);
        }
    }

    /**
     * @return mixed
     * @throws \Wootook\Core\Exception\DependencyInjection\RuntimeException
     */
    public function newInstanceWithoutConstructor()
    {
        try {
            return $this->getReflector()->newInstanceWithoutConstructor();
        } catch (\ReflectionException $e) {
            throw new CoreException\DependencyInjection\RuntimeException('Could not instantiate class : may be a PHP-internal class.', null, $e);
        }
    }

    /**
     * @return array
     */
    public function reset()
    {
        return $this->_methodDefinitions = array();
    }

    /**
     * @return null|\ReflectionClass
     */
    public function getReflector()
    {
        return $this->_reflector;
    }

    /**
     * @param \ReflectionClass $reflector
     * @return ClassDefinition
     */
    public function setReflector(\ReflectionClass $reflector)
    {
        $this->_reflector = $reflector;

        return $this;
    }
}
