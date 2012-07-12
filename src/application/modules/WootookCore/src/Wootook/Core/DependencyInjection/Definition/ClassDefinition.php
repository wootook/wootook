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
    protected $_methods = array();

    protected $_methodDefinitionHandlerClass = null;

    /**
     * @param string $className
     * @param null|string $methodDefinitionHandlerClass
     */
    public function __construct($className, $methodDefinitionHandlerClass = null)
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

    /**
     * @param string $methodName
     * @return \Wootook\Core\DependencyInjection\Definition\MethodDefinition
     */
    protected function _getMethodDefinitionInstance($methodName)
    {
        return new $this->_methodDefinitionHandlerClass($this, $methodName);
    }

    /**
     * @param string $methodName
     * @return \Wootook\Core\DependencyInjection\Definition\MethodDefinition
     * @throws \Wootook\Core\Exception\DependencyInjection\InvalidArgumentException
     */
    public function getMethodDefinition($methodName)
    {
        if (!is_string($methodName)) {
            throw new CoreException\DependencyInjection\InvalidArgumentException('Method names only accept string.');
        }

        if (!isset($this->_methods[$methodName])) {
            $this->_methods[$methodName] = $this->_getMethodDefinitionInstance($methodName);
        }

        return $this->_methods[$methodName];
    }

    public function setMethodDefinition($methodName, MethodDefinition $definition)
    {
        if (!is_string($methodName)) {
            throw new CoreException\DependencyInjection\InvalidArgumentException('Method names only accept string.');
        }

        $this->_methods[$methodName] = $definition;

        return $this;
    }

    public function getAllMethodDefinitions()
    {
        return $this->_methods;
    }

    public function newInstance(Array $args = array(), $withoutConstructorCall = false)
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
        return $this->_methods = array();
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
