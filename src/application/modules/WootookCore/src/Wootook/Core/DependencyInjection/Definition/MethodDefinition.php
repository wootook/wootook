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
    protected $_arguments = array();

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

    protected $_argumentDefinitionHandlerClass = null;

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
            $this->_argumentDefinitionHandlerClass = $argumentDefinitionHandlerClass;
        } else {
            $this->_argumentDefinitionHandlerClass = __NAMESPACE__ . '\\ArgumentDefinition';
        }

        $this->_registry = $registry;
    }

    /**
     * @param $argumentPosition
     * @return \Wootook\Core\DependencyInjection\Definition\ArgumentDefinition
     */
    protected function _getArgumentDefinitionInstance($argumentPosition)
    {
        return new $this->_argumentDefinitionHandlerClass($this, $argumentPosition);
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
     * @param string|int $argumentPosition
     * @param mixed $argumentValue
     * @return MethodDefinition
     * @throws \Wootook\Core\Exception\DependencyInjection\InvalidArgumentException
     */
    public function bindArgumentValue($argumentPosition, $argumentValue)
    {
        $this->getArgumentDefinition($argumentPosition)->bindValue($argumentValue);

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
        $this->getArgumentDefinition($argumentPosition)->bindVariable($argumentVariable);

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

        $this->getArgumentDefinition($argumentPosition)->bindRegistryEntry($registryKey);

        return $this;
    }

    /**
     * @param int|string $argumentPosition
     * @return \Wootook\Core\DependencyInjection\Definition\ArgumentDefinition
     * @throws \Wootook\Core\Exception\DependencyInjection\InvalidArgumentException
     */
    public function getArgumentDefinition($argumentPosition)
    {
        if (is_string($argumentPosition) && isset($this->_argumentIndex[$argumentPosition])) {
            $argumentPosition = $this->_argumentIndex[$argumentPosition];
        }

        if (!is_int($argumentPosition)) {
            throw new CoreException\DependencyInjection\InvalidArgumentException('Argument not found.');
        }

        if (!isset($this->_arguments[$argumentPosition])) {
            $this->_arguments[$argumentPosition] = $this->_getArgumentDefinitionInstance($argumentPosition);
        }

        return $this->_arguments[$argumentPosition];
    }

    /**
     * @return array
     */
    public function getAllArgumentDefinitions()
    {
        return $this->_arguments;
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
