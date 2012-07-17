<?php

namespace Wootook\Core\DependencyInjection\Definition;

use Wootook\Core\Base\Service,
    Wootook\Core\Config,
    Wootook\Core\DependencyInjection,
    Wootook\Core\Exception as CoreException;

class ClassDefinition
{
    protected $_className = null;
    protected $_reflector = null;
    protected $_methodDefinitions = array();

    protected $_registry = null;

    protected $_methodDefinitionClassName = 'Wootook\\Core\\DependencyInjection\\Definition\\MethodDefinition';

    /**
     * @param string $className
     * @param null|string $methodDefinitionHandlerClass
     */
    public function __construct($className, DependencyInjection\Registry $registry = null, $methodDefinitionHandlerClass = null)
    {
        $this->_className = $className;
        try {
            $this->_reflector = new \ReflectionClass($className);
        } catch (\ReflectionException $e) {
            throw new CoreException\DependencyInjection\BadMethodCallException($e->getMessage(), $e->getCode(), $e);
        }

        if (is_string($methodDefinitionHandlerClass)) {
            $this->_methodDefinitionHandlerClass = $methodDefinitionHandlerClass;
        } else {
            $this->_methodDefinitionHandlerClass = __NAMESPACE__ . '\\MethodDefinition';
        }
    }

    public function setRegistry(DependencyInjection\Registry $registry)
    {
        $this->_registry = $registry;

        return $this;
    }

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
        $class = $this->getMethodDefinitionClassName();

        return new $class($this, $methodName, $this->getRegistry());
    }

    public function registerMethodDefinition($methodName, MethodDefinition $methodDefinition)
    {
        if (!is_string($methodName)) {
            throw new CoreException\DependencyInjection\InvalidArgumentException('Method names only accept string.');
        }

        $this->_methodDefinitions[$methodName] = $methodDefinition;

        return $this;
    }

    public function addMethodDefinition($methodName)
    {
        $this->registerMethodDefinition($methodName, $this->initMethodDefinition($methodName));

        return $this;
    }

    public function setMethodDefinition($methodName, MethodDefinition $definition)
    {
        if (!is_string($methodName)) {
            throw new CoreException\DependencyInjection\InvalidArgumentException('Method names only accept string.');
        }

        $this->_methodDefinitions[$methodName] = $definition;

        return $this;
    }

    public function setMethodDefinitionClassName($methodDefinitionClassName)
    {
        if (!is_string($methodDefinitionClassName)) {
            throw new CoreException\DependencyInjection\InvalidArgumentException('Class names only accept string.');
        }

        return $this->_methodDefinitionClassName;
    }

    public function getMethodDefinitionClassName()
    {
        return $this->_methodDefinitionClassName;
    }

    public function getAllMethodDefinitions()
    {
        return $this->_methodDefinitions;
    }

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

    public function newInstanceWithoutConstructor()
    {
        try {
            return $this->getReflector()->newInstanceWithoutConstructor();
        } catch (\ReflectionException $e) {
            throw new CoreException\DependencyInjection\RuntimeException('Could not instantiate class : may be a PHP-internal class.', null, $e);
        }
    }

    public function reset()
    {
        return $this->_methodDefinitions = array();
    }

    public function getReflector()
    {
        return $this->_reflector;
    }

    public function setReflector(\ReflectionClass $reflector)
    {
        $this->_reflector = $reflector;

        return $this;
    }
}
