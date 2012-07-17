<?php

namespace Wootook\Core\DependencyInjection\Definition;

use Wootook\Core\Base\Service,
    Wootook\Core\Config,
    Wootook\Core\DependencyInjection,
    Wootook\Core\Exception as CoreException;

class MethodDefinition
{
    /**
     * @var ClassDefinition
     */
    protected $_classDefinition = null;

    /**
     * @var string
     */
    protected $_methodName = null;

    /**
     * @var \ReflectionMethod
     */
    protected $_reflector = null;

    /**
     * @var array
     */
    protected $_argumentDefinitions = array();

    /**
     * @var array
     */
    protected $_argumentIndex = array();

    /**
     * @var array
     */
    protected $_argumentReflectors = array();

    /**
     * @var null|\Wootook\Core\DependencyInjection\Registry
     */
    protected $_registry = null;

    protected $_argumentDefinitionClassName = 'Wootook\\Core\\DependencyInjection\\Definition\\ArgumentDefinition';

    /**
     * @param ClassDefinition $classDefinition
     * @param $methodName
     * @param null|\Wootook\Core\DependencyInjection\Registry $registry
     */
    public function __construct(ClassDefinition $classDefinition, $methodName, DependencyInjection\Registry $registry = null, $argumentDefinitionHandlerClass = null)
    {
        $this->_classDefinition = $classDefinition;

        $this->_methodName = $methodName;

        try {
            $this->setReflector($classDefinition->getReflector()->getMethod($methodName));
        } catch (\ReflectionException $e) {
            throw new CoreException\DependencyInjection\BadMethodCallException($e->getMessage(), $e->getCode(), $e);
        }

        if (is_string($argumentDefinitionHandlerClass)) {
            $this->setArgumentDefinitionClassName($argumentDefinitionHandlerClass);
        }

        $this->_registry = $registry;
    }

    /**
     * @param \Wootook\Core\DependencyInjection\Registry $registry
     * @return MethodDefinition
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
     * @param int|string $argumentPosition
     * @return \Wootook\Core\DependencyInjection\Definition\ArgumentDefinition
     * @throws \Wootook\Core\Exception\DependencyInjection\InvalidArgumentException
     */
    public function getArgumentDefinition($argumentPosition, $registerInstanceIfNew = false)
    {
        if (is_string($argumentPosition) && isset($this->_argumentIndex[$argumentPosition])) {
            $argumentPosition = $this->_argumentIndex[$argumentPosition];
        }

        if (!is_int($argumentPosition)) {
            throw new CoreException\DependencyInjection\InvalidArgumentException('Argument could only be found by position or by name.');
        }

        if (isset($this->_argumentDefinitions[$argumentPosition])) {
            return $this->_argumentDefinitions[$argumentPosition];
        }

        $definition = $this->initArgumentDefinition($argumentPosition);
        if ($registerInstanceIfNew === true) {
            $this->registerArgumentDefinition($argumentPosition, $definition);
        }
        return $definition;
    }

    public function initArgumentDefinition($argumentPosition)
    {
        if (is_string($argumentPosition) && isset($this->_argumentIndex[$argumentPosition])) {
            $argumentPosition = $this->_argumentIndex[$argumentPosition];
        }

        if (!is_int($argumentPosition)) {
            throw new CoreException\DependencyInjection\InvalidArgumentException('Argument could only be found by position or by name.');
        }

        $class = $this->getArgumentDefinitionClassName();

        return new $class($this, $argumentPosition, $this->getRegistry());
    }

    public function registerArgumentDefinition($argumentPosition, ArgumentDefinition $argumentDefinition)
    {
        if (is_string($argumentPosition) && isset($this->_argumentIndex[$argumentPosition])) {
            $argumentPosition = $this->_argumentIndex[$argumentPosition];
        }

        if (!is_int($argumentPosition)) {
            throw new CoreException\DependencyInjection\InvalidArgumentException('Argument could only be found by position or by name.');
        }

        $this->_argumentDefinitions[$argumentPosition] = $argumentDefinition;

        return $this;
    }

    public function addArgumentDefinition($argumentPosition)
    {
        $this->registerArgumentDefinition($argumentPosition, $this->initArgumentDefinition($argumentPosition));

        return $this;
    }

    public function setArgumentDefinition($argumentPosition, ArgumentDefinition $definition)
    {
        if (is_string($argumentPosition) && isset($this->_argumentIndex[$argumentPosition])) {
            $argumentPosition = $this->_argumentIndex[$argumentPosition];
        }

        if (!is_int($argumentPosition)) {
            throw new CoreException\DependencyInjection\InvalidArgumentException('Argument could only be found by position or by name.');
        }

        $this->_argumentDefinitions[$argumentPosition] = $definition;

        return $this;
    }

    public function setArgumentDefinitionClassName($argumentDefinitionClassName)
    {
        if (!is_string($argumentDefinitionClassName)) {
            throw new CoreException\DependencyInjection\InvalidArgumentException('Class names only accept string.');
        }

        return $this->_argumentDefinitionClassName;
    }

    public function getArgumentDefinitionClassName()
    {
        return $this->_argumentDefinitionClassName;
    }

    /**
     * @param string|int $argumentPosition
     * @param mixed $argumentValue
     * @return MethodDefinition
     * @throws \Wootook\Core\Exception\DependencyInjection\InvalidArgumentException
     */
    public function bindArgumentValue($argumentPosition, $argumentValue)
    {
        $this->getArgumentDefinition($argumentPosition, true)->bindValue($argumentValue);

        return $this;
    }

    /**
     * @param string|int $argumentPosition
     * @param mixed $argumentVariable
     * @return MethodDefinition
     * @throws \Wootook\Core\Exception\DependencyInjection\InvalidArgumentException
     */
    public function bindArgumentVariable($argumentPosition, &$argumentVariable)
    {
        $this->getArgumentDefinition($argumentPosition, true)->bindVariable($argumentVariable);

        return $this;
    }

    /**
     * @param string|int $argumentPosition
     * @param string $registryKey
     * @return MethodDefinition
     * @throws \Wootook\Core\Exception\DependencyInjection\RuntimeException
     * @throws \Wootook\Core\Exception\DependencyInjection\InvalidArgumentException
     */
    public function bindArgumentRegistryEntry($argumentPosition, $registryKey)
    {
        if (($registry = $this->getRegistry()) === null) {
            throw new CoreException\DependencyInjection\RuntimeException('No registry available');
        }

        $this->getArgumentDefinition($argumentPosition, true)->bindRegistryEntry($registryKey);

        return $this;
    }

    /**
     * @return array
     */
    public function getAllArgumentDefinitions()
    {
        return $this->_argumentDefinitions;
    }

    /**
     * @return \ReflectionMethod
     */
    public function getReflector()
    {
        return $this->_reflector;
    }

    /**
     * @param \ReflectionMethod $reflector
     * @return MethodDefinition
     */
    public function setReflector(\ReflectionMethod $reflector)
    {
        $this->_reflector = $reflector;

        foreach ($this->_reflector->getParameters() as $argumentReflector) {
            $argumentName = $argumentReflector->getName();
            $argumentPosition = $argumentReflector->getPosition();

            $this->_argumentIndex[$argumentName] = $argumentPosition;
            $this->_argumentReflectors[$argumentPosition] = $argumentReflector;
        }

        return $this;
    }

    /**
     * @param $argumentPosition
     * @return \ReflectionParameter
     * @throws \Wootook\Core\Exception\DependencyInjection\InvalidArgumentException
     */
    public function getArgumentReflector($argumentPosition)
    {
        if (is_string($argumentPosition) && isset($this->_argumentIndex[$argumentPosition])) {
            $argumentPosition = $this->_argumentIndex[$argumentPosition];
        }

        if (!is_int($argumentPosition) || !isset($this->_argumentReflectors[$argumentPosition])) {
            throw new CoreException\DependencyInjection\InvalidArgumentException('Argument not found.');
        }

        return $this->_argumentReflectors[$argumentPosition];
    }
}
