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

    protected function _getArgumentDefinitionInstance($argumentPosition)
    {
        return new $this->_argumentDefinitionHandlerClass($this, $argumentPosition);
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

    public function bindArgumentValue($argumentPosition, $argumentValue)
    {
        if (is_numeric($argumentPosition)) {
            $this->_arguments[$argumentPosition] = $argumentValue;
        } else if (is_string($argumentPosition) && isset($this->_argumentIndex[$argumentPosition])) {
            $this->_arguments[$this->_argumentIndex[$argumentPosition]] = $argumentValue;
        } else {
            throw new CoreException\DependencyInjection\InvalidArgumentException('No such argument');
        }

        return $this;
    }

    public function bindArgumentVariable($argumentPosition, &$argumentVariable)
    {
        if (is_numeric($argumentPosition)) {
            if (is_object($argumentVariable)) {
                $this->_arguments[$argumentPosition] = $argumentVariable;
            } else {
                $this->_arguments[$argumentPosition] = &$argumentVariable;
            }
        } else if (is_string($argumentPosition) && isset($this->_argumentIndex[$argumentPosition])) {
            if (is_object($argumentVariable)) {
                $this->_arguments[$this->_argumentIndex[$argumentPosition]] = $argumentVariable;
            } else {
                $this->_arguments[$this->_argumentIndex[$argumentPosition]] = &$argumentVariable;
            }
        } else {
            throw new CoreException\DependencyInjection\InvalidArgumentException('No such argument');
        }

        return $this;
    }

    public function bindArgumentRegistry($argumentPosition, $argumentKey)
    {
        if (($registry = $this->getRegistry()) === null) {
            throw new CoreException\DependencyInjection\RuntimeException('No registry available');
        }

        if (is_numeric($argumentPosition)) {
            $this->_arguments[$argumentPosition] = $argumentVariable;
        } else if (is_string($argumentPosition) && isset($this->_argumentIndex[$argumentPosition])) {
            $this->_arguments[$this->_argumentIndex[$argumentPosition]] = $argumentVariable;
        } else {
            throw new CoreException\DependencyInjection\InvalidArgumentException('No such argument');
        }

        return $this;
    }

    public function getArgumentDefinition($argumentPosition)
    {
        if (is_numeric($argumentPosition)) {
            if (!isset($this->_arguments[$argumentPosition])) {
                $this->_arguments[$argumentPosition] = $this->_getArgumentDefinitionInstance($argumentPosition);
            }

            return $this->_arguments[$argumentPosition];
        } else if (is_string($argumentPosition) && isset($this->_argumentIndex[$argumentPosition])) {
            if (!isset($this->_arguments[$this->_argumentIndex[$argumentPosition]])) {
                $this->_arguments[$this->_argumentIndex[$argumentPosition]] = $this->_getArgumentDefinitionInstance($this->_argumentIndex[$argumentPosition]);
            }

            return $this->_arguments[$this->_argumentIndex[$argumentPosition]];
        } else {
            throw new CoreException\InvalidArgumentException('Argument not found.');
        }
    }

    public function getAllArgumentDefinitions()
    {
        return $this->_arguments;
    }

    public function getReflector()
    {
        return $this->_reflector;
    }

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

    public function getArgumentReflector($argumentPosition)
    {
        if (is_numeric($argumentPosition)) {
            return $this->_argumentReflectors[$argumentPosition];
        } else if (is_string($argumentPosition) && isset($this->_argumentIndex[$argumentPosition])) {
            return $this->_argumentReflectors[$this->_argumentIndex[$argumentPosition]];
        } else {
            throw new CoreException\DependencyInjection\InvalidArgumentException('No such argument');
        }
    }
}
