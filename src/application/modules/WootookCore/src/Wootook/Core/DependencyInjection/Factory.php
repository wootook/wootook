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

    protected function _construct(Registry $registry = null)
    {
        if ($registry !== null) {
            $this->setRegistry($registry);
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

    public function getClassDefinition($className)
    {
        if (isset($this->_classDefinitions[$className])) {
            return $this->_classDefinitions[$className];
        }

        return $this->initClassDefinition($className);
    }

    public function initClassDefinition($className)
    {
        $class = $this->getClassDefinitionClassName();
        return new $class($className);
    }

    public function registerClassDefinition($className, Definition\ClassDefinition $classDefinition)
    {
        $this->_classDefinitions[$className] = $classDefinition;

        return $this;
    }

    public function addClassDefinition($className)
    {
        $this->registerClassDefinition($className, $this->initClassDefinition($className));

        return $this;
    }

    public function setClassDefinitionClassName($className)
    {
        $this->_classDefinitionClassName = $className;

        return $this;
    }

    public function getClassDefinitionClassName()
    {
        return $this->_classDefinitionClassName;
    }

    public function __invoke($className, Array $additionalArguments = array())
    {
        $definition = $this->getClassDefinition($className);

        return $definition->newInstance($additionalArguments);
    }
}
