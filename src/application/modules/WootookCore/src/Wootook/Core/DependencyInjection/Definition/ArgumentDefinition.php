<?php

namespace Wootook\Core\DependencyInjection\Definition;

use Wootook\Core\Base\Service,
    Wootook\Core\Config,
    Wootook\Core\DependencyInjection,
    Wootook\Core\Exception as CoreException;

class ArgumentDefinition
{
    const TYPE_VALUE    = 0x01;
    const TYPE_VARIABLE = 0x02;
    const TYPE_REGISTRY  = 0x03;

    /**
     * @var ClassDefinition
     */
    protected $_methodDefinition = null;

    /**
     * @var string
     */
    protected $_argumentName = null;

    /**
     * @var \ReflectionParameter
     */
    protected $_reflector = null;

    /**
     * @var array
     */
    protected $_value = null;

    /**
     * @var array
     */
    protected $_type = null;

    /**
     * @var null|\Wootook\Core\DependencyInjection\Registry
     */
    protected $_registry = null;

    /**
     * @param ClassDefinition $classDefinition
     * @param $methodName
     * @param null|\Wootook\Core\DependencyInjection\Registry $registry
     */
    public function __construct(MethodDefinition $methodDefinition, $argumentName, DependencyInjection\Registry $registry = null)
    {
        $this->_methodDefinition = $methodDefinition;
        $this->_argumentName = $argumentName;

        $this->setReflector($methodDefinition->getArgumentReflector($argumentName));

        $this->_registry = $registry;
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

    public function bindValue($argumentValue)
    {
        $this->_value = $argumentValue;
        $this->_type = self::TYPE_VALUE;

        return $this;
    }

    public function bindVariable(&$argumentVariable)
    {
        $this->_value = &$argumentVariable;
        $this->_type = self::TYPE_VARIABLE;

        return $this;
    }

    public function bindRegistryEntry($registryKey)
    {
        $this->_value = $registryKey;
        $this->_type = self::TYPE_REGISTRY;

        return $this;
    }

    public function getType()
    {
        return $this->_type;
    }

    public function getValue()
    {
        return $this->_value;
    }

    public function getReflector()
    {
        return $this->_reflector;
    }

    public function setReflector(\ReflectionParameter $reflector)
    {
        $this->_reflector = $reflector;

        return $this;
    }

    public function compileInto(Array &$compiledArgs)
    {
        $position = $this->getReflector()->getPosition();

        if ($this->_type === self::TYPE_VALUE) {
            $compiledArgs[$position] = $this->_value;
        } else if ($this->_type === self::TYPE_VARIABLE) {
            $compiledArgs[$position] = &$this->_value;
        } else if ($this->_type === self::TYPE_REGISTRY) {
            if (($registry = $this->getRegistry()) === null) {
                throw new CoreException\DependencyInjection\RuntimeException('No registry available');
            }

            $compiledArgs[$position] = $registry->get($this->_value);
        }
    }
}
