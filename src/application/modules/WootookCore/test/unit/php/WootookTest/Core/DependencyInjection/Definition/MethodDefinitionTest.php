<?php

namespace WootookTest\Core\DependencyInjection\Definition;

use Wootook\Core\Config,
    Wootook\Core\DependencyInjection\Definition;

class MethodDefinitionTest extends \PHPUnit_Framework_TestCase
{
    public function methodToTest($fooArgumentOne, $fooArgumentTwo = null)
    {
        // This method exists only for testing method introspection with the Dependency Injection component
    }

    public function testMethodDefinitionInstantiation_withNonExistingMethod()
    {
        $mock = $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\ClassDefinition', array(), array(), '', false);

        $mock->expects($this->once())
            ->method('getReflector')
            ->will($this->returnValue(new \ReflectionClass(__CLASS__)))
        ;

        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\BadMethodCallException');
        new Definition\MethodDefinition($mock, 'inexistentMethod');
    }

    public function testMethodDefinitionInstantiation_withoutRegistry_withSpecialArgumentDefinitionClassName()
    {
        $argumentDefinitionClassName = \uniqid('WootookTest_Core_DependencyInjection_Mock_ArgumentDefinition_TestNewInstance_');
        $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\ArgumentDefinition', array(), array(), $argumentDefinitionClassName, false);

        $mock = $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\ClassDefinition', array(), array(), '', false);

        $mock->expects($this->once())
            ->method('getReflector')
            ->will($this->returnValue(new \ReflectionClass(__CLASS__)))
        ;

        $definition = new Definition\MethodDefinition($mock, 'methodToTest', null, $argumentDefinitionClassName);

        $this->assertInstanceOf($argumentDefinitionClassName, $definition->getArgumentDefinition('fooArgumentOne'));
    }

    public function testMethodDefinitionAccessors_getReflector()
    {
        $mock = $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\ClassDefinition', array(), array(), '', false);

        $mock->expects($this->once())
            ->method('getReflector')
            ->will($this->returnValue(new \ReflectionClass(__CLASS__)))
        ;

        $definition = new Definition\MethodDefinition($mock, __FUNCTION__);

        $this->assertInstanceOf('\\ReflectionMethod', $definition->getReflector());
    }

    public function testMethodDefinitionMutators_setReflector()
    {
        $mock = $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\ClassDefinition', array(), array(), '', false);

        $mock->expects($this->once())
            ->method('getReflector')
            ->will($this->returnValue(new \ReflectionClass(__CLASS__)))
        ;

        $definition = new Definition\MethodDefinition($mock, __FUNCTION__);
        $reflector = new \ReflectionMethod(__CLASS__, __FUNCTION__);
        $definition->setReflector($reflector);

        $this->assertSame($reflector, $definition->getReflector());
    }
    /*
    public function testBuildWithoutConstructorArguments_callBindConstructorArgumentValue()
    {
        $definition = new Definition\ClassDefinition('MyNamespace\\MyClass');

        $definition->bindConstructorArgumentValue(0, 36);
        $this->assertEquals(36, $definition->getConstructorArgument(0));
    }

    public function testBuildWithoutConstructorArguments_callBindConstructorArgumentVariable()
    {
        $definition = new Definition\ClassDefinition('MyNamespace\\MyClass');

        $variable = 36;
        $definition->bindConstructorArgumentVariable(0, $variable);

        $variable = 12;
        $this->assertEquals(12, $definition->getConstructorArgument(0));
        $this->assertSame($variable, $definition->getConstructorArgument(0));
    }

    public function testBuildWithoutConstructorArguments_callBindConstructorArgumentRegistry()
    {
        $definition = new Definition\ClassDefinition('MyNamespace\\MyClass');
        $registry = $this->getMock('Wootook\\Core\\DependencyInjection\\Registry');

        $object = new \StdClass();
        $registry->expects($this->once())
            ->method('get')
            ->with('my_object')
            ->will($this->returnValue($object))
        ;

        $definition->setRegistry($registry);
        $definition->bindConstructorArgumentRegistry(0, 'my_object');

        $this->assertSame($object, $definition->getConstructorArgument(0));
    }
    */
}
