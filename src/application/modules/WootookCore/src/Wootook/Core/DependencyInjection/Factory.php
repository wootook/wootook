<?php

namespace Wootook\Core\DependencyInjection;

use Wootook\Core\Base\Service,
    Wootook\Core\Exception as CoreException,
    Wootook\Core\Config;

class Factory
{
    use Service\App;

    protected $_registry = null;

    protected $_classDefinitions = array();

    protected $_classDefinitionClassName = 'Wootook\\Core\\DependencyInjection\\Definition\\ClassDefinition';

    protected function _construct(Registry $registry = null, $classDefinitionClassName = null)
    {
        if ($registry !== null) {
            $this->setRegistry($registry);
        }

        if ($classDefinitionClassName !== null) {
            $this->setClassDefinitionClassName($classDefinitionClassName);
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

    public function getClassDefinition($className, $registerInstanceIfNew = false)
    {
        if (!is_string($className)) {
            throw new CoreException\DependencyInjection\InvalidArgumentException('Class names only accept string.');
        }

        if (isset($this->_classDefinitions[$className])) {
            return $this->_classDefinitions[$className];
        }

        $definition = $this->initClassDefinition($className);
        if ($registerInstanceIfNew === true) {
            $this->registerClassDefinition($className, $definition);
        }
        return $definition;

        return $this->initClassDefinition($className);
    }

    public function initClassDefinition($className)
    {
        $class = $this->getClassDefinitionClassName();

        return new $class($className);
    }

    public function registerClassDefinition($className, Definition\ClassDefinition $classDefinition)
    {
        if (!is_string($className)) {
            throw new CoreException\DependencyInjection\InvalidArgumentException('Class names only accept string.');
        }

        $this->_classDefinitions[$className] = $classDefinition;

        return $this;
    }

    public function addClassDefinition($className)
    {
        $this->registerClassDefinition($className, $this->initClassDefinition($className));

        return $this;
    }

    public function setClassDefinition($className, Definition\ClassDefinition $definition)
    {
        if (!is_string($className)) {
            throw new CoreException\DependencyInjection\InvalidArgumentException('Class names only accept string.');
        }

        $this->_classDefinitions[$className] = $definition;

        return $this;
    }

    public function getAllClassDefinitions()
    {
        return $this->_classDefinitions;
    }

    public function setClassDefinitionClassName($classDefinitionClassName)
    {
        if (!is_string($classDefinitionClassName)) {
            throw new CoreException\DependencyInjection\InvalidArgumentException('Class names only accept string.');
        }

        $this->_classDefinitionClassName = $classDefinitionClassName;

        return $this;
    }

    public function getClassDefinitionClassName()
    {
        return $this->_classDefinitionClassName;
    }

    public function reset()
    {
        return $this->_classDefinitions = array();
    }

    public function __invoke($className, Array $additionalArguments = array())
    {
        $definition = $this->getClassDefinition($className);

        return $definition->newInstance($additionalArguments);
    }
}
