<?php

namespace Wootook\Core\DependencyInjection;

use Wootook\Core\Base\Service,
    Wootook\Core\Exception as CoreException,
    Wootook\Core\Config;

class Factory
{
    use Service\App;

    protected $_registry = null;

    protected $_definitions = array();

    protected function _construct(Config\Node $config, Registry $registry = null)
    {
        if ($registry !== null) {
            $this->setRegistry($registry);
        }

        foreach ($config as $className => $constructorArguments) {
            $definition = $this->getDefinition($className);

            if ($constructorArguments === null) {
                continue;
            }

            foreach ($constructorArguments as $argumentId => $argumentConfig) {
                $argumentType = key($argumentConfig);
                $argumentValue = key($argumentConfig);
                $definition->addConstructorArgument($argumentId, $argumentType, $argumentValue);
            }
        }
    }

    public function setRegistry(Registry $registry)
    {
        $this->_registry = $registry;

        return $this;
    }

    public function getRegistry()
    {
        if ($this->_registry === null) {
            $this->_registry = new Registry($this->app());
        }

        return $this->_registry;
    }

    public function getDefinition($className)
    {
        if (!isset($this->_definitions[$className])) {
            $this->_definitions[$className] = new Definition\ClassDefinition($className);
        }

        return $this->_definitions[$className];
    }

    public function get($className, Array $additionalArguments = array())
    {
        $definition = $this->getDefinition($className);

        try {
            $reflectionClass = new \ReflectionClass($className);
        } catch (\ReflectionException $e) {
            throw new CoreException\RuntimeException(sprintf('Class "%s" not found', $className), null, $e);
        }

        if (!$reflectionClass->hasMethod('__construct')) {
            return $reflectionClass->newInstance();
        }

        try {
            $reflectionMethod = $reflectionClass->getMethod('__construct');
        } catch (\ReflectionException $e) {
            throw new CoreException\RuntimeException(sprintf('Class "%s" has no contructor', $className), null, $e);
        }

        $constructorArguments = $definition->prepareArguments($reflectionMethod, $this->getRegistry(), $additionalArguments);

        try {
            return $reflectionClass->newInstanceArgs($constructorArguments);
        } catch (\ReflectionException $e) {
            throw new CoreException\RuntimeException(sprintf('Class "%s" could not be instanciated', $className), null, $e);
        }
    }
}
