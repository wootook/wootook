<?php

namespace WootookUnit\Core\DependencyInjection\Definition;

use Wootook\Core\Config,
    Wootook\Core\DependencyInjection,
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
        $argumentDefinitionClassName = \uniqid('WootookUnit_Core_DependencyInjection_Mock_ArgumentDefinition_TestNewInstance_');
        $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\ArgumentDefinition', array(), array(), $argumentDefinitionClassName, false);

        $mock = $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\ClassDefinition', array(), array(), '', false);

        $mock->expects($this->once())
            ->method('getReflector')
            ->will($this->returnValue(new \ReflectionClass(__CLASS__)))
        ;

        $definition = new Definition\MethodDefinition($mock, 'methodToTest', null, $argumentDefinitionClassName);

        $this->assertInstanceOf($argumentDefinitionClassName, $definition->getArgumentDefinition('fooArgumentOne'));
    }

    public function testMethodDefinitionInstantiation_withRegistry_withSpecialArgumentDefinitionClassName()
    {
        $app = $this->getMock('Wootook\\Core\\App\\App', array(), array(), '', false);
        /** @var \Wootook\Core\DependencyInjection\Registry $registry */
        $registry = $this->getMock('Wootook\\Core\\DependencyInjection\\Registry', array(), array($app));

        $argumentDefinitionClassName = \uniqid('WootookUnit_Core_DependencyInjection_Mock_ArgumentDefinition_TestNewInstance_');
        $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\ArgumentDefinition', array(), array(), $argumentDefinitionClassName, false);

        /** @var \Wootook\Core\DependencyInjection\Definition\ClassDefinition $mock */
        $mock = $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\ClassDefinition', array('getReflector'), array(), '', false);

        $mock->expects($this->once())
            ->method('getReflector')
            ->will($this->returnValue(new \ReflectionClass(__CLASS__)))
        ;

        $definition = new Definition\MethodDefinition($mock, 'methodToTest', $registry, $argumentDefinitionClassName);

        $this->assertInstanceOf($argumentDefinitionClassName, $definition->getArgumentDefinition('fooArgumentOne'));
    }

    public function testMethodDefinitionAccessors_getReflector()
    {
        $mock = $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\ClassDefinition', array('getReflector'), array(), '', false);

        $mock->expects($this->once())
            ->method('getReflector')
            ->will($this->returnValue(new \ReflectionClass(__CLASS__)))
        ;

        $definition = new Definition\MethodDefinition($mock, __FUNCTION__);

        $this->assertInstanceOf('\\ReflectionMethod', $definition->getReflector());
    }

    public function testMethodDefinitionMutators_setReflector()
    {
        $mock = $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\ClassDefinition', array('getReflector'), array(), '', false);

        $mock->expects($this->once())
            ->method('getReflector')
            ->will($this->returnValue(new \ReflectionClass(__CLASS__)))
        ;

        $definition = new Definition\MethodDefinition($mock, __FUNCTION__);
        $reflector = new \ReflectionMethod(__CLASS__, __FUNCTION__);
        $definition->setReflector($reflector);

        $this->assertSame($reflector, $definition->getReflector());
    }

    public function testMethodDefinition_accessorAndMutatorForRegistry()
    {
        $registryMock = $this->getMock('Wootook\\Core\\DependencyInjection\\Registry', array(), array(), '', false);
        $classDefinitionMock = $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\ClassDefinition', array('getReflector'), array(), '', false);

        $classDefinitionMock->expects($this->once())
            ->method('getReflector')
            ->will($this->returnValue(new \ReflectionClass(__CLASS__)))
        ;

        $definition = new Definition\MethodDefinition($classDefinitionMock, __FUNCTION__);
        $definition->setRegistry($registryMock);

        $this->assertSame($registryMock, $definition->getRegistry());
    }

    public function testMethodDefinition_bindArgumentValue_withNumericArgumentIndex()
    {
        $classDefinition = new Definition\ClassDefinition(__CLASS__);

        $definition = $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\MethodDefinition',
            array('getArgumentDefinition'), array($classDefinition, 'methodToTest'));

        $definition->expects($this->once())
            ->method('getArgumentDefinition')
            ->with(0)
            ->will($this->returnValue(new Definition\ArgumentDefinition($definition, 0)))
        ;

        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $definition */
        $definition->bindArgumentValue(0, 65);
    }

    public function testMethodDefinition_bindArgumentVariable_withNumericArgumentIndex()
    {
        $classDefinition = new Definition\ClassDefinition(__CLASS__);

        $definition = $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\MethodDefinition',
            array('getArgumentDefinition'), array($classDefinition, 'methodToTest'));

        $definition->expects($this->once())
            ->method('getArgumentDefinition')
            ->with(0)
            ->will($this->returnValue(new Definition\ArgumentDefinition($definition, 0)))
        ;

        $variable = 65;
        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $definition */
        $definition->bindArgumentVariable(0, $variable);
    }

    public function testMethodDefinition_bindArgumentRegistryEntry_withNumericArgumentIndex()
    {
        $classDefinition = new Definition\ClassDefinition(__CLASS__);
        $registry = $this->getMockBuilder('Wootook\\Core\\DependencyInjection\\Registry')
            ->disableOriginalConstructor()
            ->setMethods(array('get'))
            ->getMock();

        $registry->expects($this->any())->method('get')->will($this->returnValue(36));

        $definition = $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\MethodDefinition',
            array('getArgumentDefinition'), array($classDefinition, 'methodToTest', $registry));

        $definition->expects($this->once())
            ->method('getArgumentDefinition')
            ->with(0)
            ->will($this->returnValue(new Definition\ArgumentDefinition($definition, 0, $registry)))
        ;

        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $definition */
        $definition->bindArgumentRegistryEntry(0, 'foo');
    }

    public function testMethodDefinition_bindArgumentValue_withNamedArgumentIndex()
    {
        $classDefinition = new Definition\ClassDefinition(__CLASS__);

        $definition = $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\MethodDefinition',
            array('getArgumentDefinition'), array($classDefinition, 'methodToTest'));

        $definition->expects($this->once())
            ->method('getArgumentDefinition')
            ->with('fooArgumentOne')
            ->will($this->returnValue(new Definition\ArgumentDefinition($definition, 0)))
        ;

        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $definition */
        $definition->bindArgumentValue('fooArgumentOne', 65);
    }

    public function testMethodDefinition_bindArgumentVariable_withNamedArgumentIndex()
    {
        $classDefinition = new Definition\ClassDefinition(__CLASS__);

        $definition = $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\MethodDefinition',
            array('getArgumentDefinition'), array($classDefinition, 'methodToTest'));

        $definition->expects($this->once())
            ->method('getArgumentDefinition')
            ->with('fooArgumentOne')
            ->will($this->returnValue(new Definition\ArgumentDefinition($definition, 0)))
        ;

        $variable = 65;
        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $definition */
        $definition->bindArgumentVariable('fooArgumentOne', $variable);
    }

    public function testMethodDefinition_bindArgumentRegistryEntry_withNamedArgumentIndex()
    {
        $classDefinition = new Definition\ClassDefinition(__CLASS__);
        $registry = $this->getMockBuilder('Wootook\\Core\\DependencyInjection\\Registry')
            ->disableOriginalConstructor()
            ->setMethods(array('get'))
            ->getMock();

        $registry->expects($this->any())->method('get')->will($this->returnValue(36));

        $definition = $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\MethodDefinition',
            array('getArgumentDefinition'), array($classDefinition, 'methodToTest', $registry));

        $definition->expects($this->once())
            ->method('getArgumentDefinition')
            ->with('fooArgumentOne')
            ->will($this->returnValue(new Definition\ArgumentDefinition($definition, 0, $registry)))
        ;

        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $definition */
        $definition->bindArgumentRegistryEntry('fooArgumentOne', 'foo');
    }

    public function testMethodDefinition_bindArgumentRegistryEntry_withNoRegistry()
    {
        $classDefinition = $this->getMockBuilder('Wootook\\Core\\DependencyInjection\\Definition\\ClassDefinition')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $classDefinition->expects($this->once())
            ->method('getReflector')
            ->will($this->returnValue(new \ReflectionClass(__CLASS__)))
        ;

        $definition = $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\MethodDefinition',
            array('getArgumentDefinition'), array($classDefinition, 'methodToTest'));

        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\RuntimeException');

        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $definition */
        $definition->bindArgumentRegistryEntry('fooArgumentOne', 'foo');
    }

    public function testMethodDefinition_getArgumentDefinition_withInvalidArgumentId()
    {
        $classDefinition = $this->getMockBuilder('Wootook\\Core\\DependencyInjection\\Definition\\ClassDefinition')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $classDefinition->expects($this->once())
            ->method('getReflector')
            ->will($this->returnValue(new \ReflectionClass(__CLASS__)))
        ;

        $definition = new Definition\MethodDefinition($classDefinition, 'methodToTest');

        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\InvalidArgumentException');

        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $definition */
        $definition->getArgumentDefinition(1.5);
    }

    public function testMethodDefinition_getArgumentReflector_withNumericArgumentId()
    {
        $classDefinition = $this->getMockBuilder('Wootook\\Core\\DependencyInjection\\Definition\\ClassDefinition')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $classDefinition->expects($this->once())
            ->method('getReflector')
            ->will($this->returnValue(new \ReflectionClass(__CLASS__)))
        ;

        $definition = new Definition\MethodDefinition($classDefinition, 'methodToTest');

        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $definition */
        $reflector = $definition->getArgumentReflector(0);
        $this->assertInstanceOf('ReflectionParameter', $reflector);
        $this->assertEquals(0, $reflector->getPosition());
    }

    public function testMethodDefinition_getArgumentReflector_withNamedArgumentId()
    {
        $classDefinition = $this->getMockBuilder('Wootook\\Core\\DependencyInjection\\Definition\\ClassDefinition')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $classDefinition->expects($this->once())
            ->method('getReflector')
            ->will($this->returnValue(new \ReflectionClass(__CLASS__)))
        ;

        $definition = new Definition\MethodDefinition($classDefinition, 'methodToTest');

        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $definition */
        $reflector = $definition->getArgumentReflector('fooArgumentOne');
        $this->assertInstanceOf('ReflectionParameter', $reflector);
        $this->assertEquals(0, $reflector->getPosition());
    }

    public function testMethodDefinition_getArgumentReflector_withInvalidArgumentId()
    {
        $classDefinition = $this->getMockBuilder('Wootook\\Core\\DependencyInjection\\Definition\\ClassDefinition')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $classDefinition->expects($this->once())
            ->method('getReflector')
            ->will($this->returnValue(new \ReflectionClass(__CLASS__)))
        ;

        $definition = new Definition\MethodDefinition($classDefinition, 'methodToTest');

        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\InvalidArgumentException');

        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $definition */
        $definition->getArgumentReflector(1.5);
    }
}
