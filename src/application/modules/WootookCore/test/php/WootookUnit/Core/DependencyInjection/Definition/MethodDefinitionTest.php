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

    /**
     * @param array $additionalMethods
     * @return \Wootook\Core\DependencyInjection\Definition\ClassDefinition
     */
    public function getClassDefinitionMock(Array $additionalMethods = array())
    {
        $methods = array_merge($additionalMethods, array('getReflector'));

        $classDefinition = $this->getMockBuilder('Wootook\\Core\\DependencyInjection\\Definition\\ClassDefinition')
            ->setMethods($methods)
            ->disableOriginalConstructor(true)
            ->getMock()
        ;

        $classDefinition->expects($this->once())
            ->method('getReflector')
            ->will($this->returnValue(new \ReflectionClass(__CLASS__)))
        ;

        return $classDefinition;
    }

    /**
     * @return \Wootook\Core\DependencyInjection\Definition\ArgumentDefinition
     */
    public function getArgumentDefinitionMock(Array $additionalMethods = array())
    {
        $argumentDefinition = $this->getMockBuilder('Wootook\\Core\\DependencyInjection\\Definition\\ArgumentDefinition')
            ->setMethods($additionalMethods)
            ->disableOriginalConstructor(true)
            ->getMock()
        ;

        return $argumentDefinition;
    }

    /**
     * @return \Wootook\Core\DependencyInjection\Registry
     */
    public function getRegistryMock(Array $additionalMethods = array())
    {
        $registry = $this->getMockBuilder('Wootook\\Core\\DependencyInjection\\Registry')
            ->setMethods($additionalMethods)
            ->disableOriginalConstructor(true)
            ->getMock()
        ;

        return $registry;
    }

    public function testMethodDefinitionInstantiation_withNonExistingMethod()
    {
        /** @var \Wootook\Core\DependencyInjection\Definition\ClassDefinition $classDefinition */
        $classDefinition = $this->getClassDefinitionMock();

        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\BadMethodCallException');
        new Definition\MethodDefinition($classDefinition, 'inexistentMethod');
    }

    public function testMethodDefinitionInstantiation_withExistingMethod()
    {
        /** @var \Wootook\Core\DependencyInjection\Definition\ClassDefinition $classDefinition */
        $classDefinition = $this->getClassDefinitionMock();

        new Definition\MethodDefinition($classDefinition, 'methodToTest');
    }

    public function testMethodDefinitionInstantiation_withoutRegistry_withSpecialArgumentDefinitionClassName()
    {
        /** @var \Wootook\Core\DependencyInjection\Definition\ClassDefinition $classDefinition */
        $classDefinition = $this->getClassDefinitionMock();

        $definition = new Definition\MethodDefinition($classDefinition, 'methodToTest', null, 'stdClass');

        $this->assertEquals('stdClass', $definition->getArgumentDefinitionClassName());
    }

    public function testMethodDefinitionInstantiation_withRegistry_withSpecialArgumentDefinitionClassName()
    {
        /** @var \Wootook\Core\DependencyInjection\Registry $registry */
        $registry = $this->getRegistryMock();

        /** @var \Wootook\Core\DependencyInjection\Definition\ClassDefinition $classDefinition */
        $classDefinition = $this->getClassDefinitionMock();

        $definition = new Definition\MethodDefinition($classDefinition, 'methodToTest', $registry, 'stdClass');

        $this->assertEquals('stdClass', $definition->getArgumentDefinitionClassName());
        $this->assertSame($registry, $definition->getRegistry());
    }

    public function testMethodDefinitionInstantiation_withInvalidSpecialArgumentDefinitionClassName()
    {
        /** @var \Wootook\Core\DependencyInjection\Registry $registry */
        $registry = $this->getRegistryMock();

        /** @var \Wootook\Core\DependencyInjection\Definition\ClassDefinition $classDefinition */
        $classDefinition = $this->getClassDefinitionMock();

        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\InvalidArgumentException');
        $definition = new Definition\MethodDefinition($classDefinition, 'methodToTest', $registry, 32);
    }

    public function testMethodDefinitionInstantiation_validateReflectorInstance()
    {
        /** @var \Wootook\Core\DependencyInjection\Definition\ClassDefinition $classDefinition */
        $classDefinition = $this->getClassDefinitionMock();

        $definition = new Definition\MethodDefinition($classDefinition, 'methodToTest');

        $this->assertInstanceOf('ReflectionMethod', $definition->getReflector());
        $this->assertEquals('methodToTest', $definition->getReflector()->getName());
    }

    public function testMethodDefinitionAccessorsAndMutators_usingReflector()
    {
        /** @var \Wootook\Core\DependencyInjection\Definition\ClassDefinition $classDefinition */
        $classDefinition = $this->getClassDefinitionMock();

        $definition = new Definition\MethodDefinition($classDefinition, 'methodToTest');

        $this->assertInstanceOf('ReflectionMethod', $definition->getReflector());

        $reflector = new \ReflectionMethod(__CLASS__, 'methodToTest');
        $definition->setReflector($reflector);

        $this->assertSame($reflector, $definition->getReflector());
    }

    public function testMethodDefinitionAccessors_usingArgumentReflector()
    {
        /** @var \Wootook\Core\DependencyInjection\Definition\ClassDefinition $classDefinition */
        $classDefinition = $this->getClassDefinitionMock();

        $definition = new Definition\MethodDefinition($classDefinition, 'methodToTest');

        $reflector = $definition->getArgumentReflector(0);
        $this->assertInstanceOf('ReflectionParameter', $reflector);
        $this->assertEquals('fooArgumentOne', $reflector->getName());
        $this->assertEquals(0, $reflector->getPosition());

        $reflector = $definition->getArgumentReflector('fooArgumentOne');
        $this->assertInstanceOf('ReflectionParameter', $reflector);
        $this->assertEquals('fooArgumentOne', $reflector->getName());
        $this->assertEquals(0, $reflector->getPosition());

        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\BadMethodCallException');
        $definition->getArgumentReflector(array());
    }

    public function testMethodDefinitionArgumentDefinitions_initArgumentDefinition()
    {
        /** @var \Wootook\Core\DependencyInjection\Definition\ClassDefinition $classDefinition */
        $classDefinition = $this->getClassDefinitionMock();

        $definition = new Definition\MethodDefinition($classDefinition, 'methodToTest', null, 'stdClass');

        $argumentDefinition = $definition->initArgumentDefinition(0);
        $this->assertInstanceOf('stdClass', $argumentDefinition);

        $argumentDefinition = $definition->initArgumentDefinition('fooArgumentOne');
        $this->assertInstanceOf('stdClass', $argumentDefinition);

        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\InvalidArgumentException');
        $definition->initArgumentDefinition(array());
    }

    public function testMethodDefinitionArgumentDefinitions_registerArgumentDefinition()
    {
        /** @var \Wootook\Core\DependencyInjection\Definition\ClassDefinition $classDefinition */
        $classDefinition = $this->getClassDefinitionMock();

        /** @var \Wootook\Core\DependencyInjection\Definition\ArgumentDefinition $argumentDefinition */
        $argumentDefinition = $this->getArgumentDefinitionMock();

        $definition = new Definition\MethodDefinition($classDefinition, 'methodToTest');

        $definition->registerArgumentDefinition(0, $argumentDefinition);
        $this->assertCount(1, $definition->getAllArgumentDefinitions());

        $definition->registerArgumentDefinition('fooArgumentOne', $argumentDefinition);
        $this->assertCount(1, $definition->getAllArgumentDefinitions());

        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\InvalidArgumentException');
        $definition->registerArgumentDefinition(array(), $argumentDefinition);
    }

    public function testMethodDefinitionArgumentDefinitions_addArgumentDefinition()
    {
        /** @var \Wootook\Core\DependencyInjection\Definition\ClassDefinition $classDefinition */
        $classDefinition = $this->getClassDefinitionMock();

        /** @var \Wootook\Core\DependencyInjection\Definition\ArgumentDefinition $argumentDefinition */
        $argumentDefinition = $this->getArgumentDefinitionMock(array('__construct'));

        $definition = new Definition\MethodDefinition($classDefinition, 'methodToTest', null, get_class($argumentDefinition));

        $definition->addArgumentDefinition(0);
        $this->assertCount(1, $definition->getAllArgumentDefinitions());
        $this->assertInstanceOf(get_class($argumentDefinition), $definition->getAllArgumentDefinitions()[0]);
    }

    public function testMethodDefinitionArgumentDefinitions_setArgumentDefinition()
    {
        /** @var \Wootook\Core\DependencyInjection\Definition\ClassDefinition $classDefinition */
        $classDefinition = $this->getClassDefinitionMock();

        /** @var \Wootook\Core\DependencyInjection\Definition\ArgumentDefinition $argumentDefinition */
        $argumentDefinition = $this->getArgumentDefinitionMock();

        $definition = new Definition\MethodDefinition($classDefinition, 'methodToTest', null, 'stdClass');

        $definition->setArgumentDefinition(0, $argumentDefinition);
        $this->assertCount(1, $definition->getAllArgumentDefinitions());
        $this->assertInstanceOf(get_class($argumentDefinition), $definition->getAllArgumentDefinitions()[0]);

        $definition->setArgumentDefinition('fooArgumentOne', $argumentDefinition);
        $this->assertCount(1, $definition->getAllArgumentDefinitions());
        $this->assertInstanceOf(get_class($argumentDefinition), $definition->getAllArgumentDefinitions()[0]);

        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\InvalidArgumentException');
        $definition->setArgumentDefinition(array(), $argumentDefinition);
    }

    public function testMethodDefinitionBindings_bindArgumentValueWithNumericArgumentIndex()
    {
        /** @var \Wootook\Core\DependencyInjection\Definition\ClassDefinition $classDefinition */
        $classDefinition = $this->getClassDefinitionMock();

        $definition = $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\MethodDefinition',
            array('getArgumentDefinition'), array($classDefinition, 'methodToTest'));

        $argumentDefinition = $this->getArgumentDefinitionMock(array('bindValue'));

        $definition->expects($this->once())
            ->method('getArgumentDefinition')
            ->with(0, true)
            ->will($this->returnValue($argumentDefinition))
        ;

        $argumentDefinition->expects($this->once())
            ->method('bindValue')
            ->with(65)
        ;

        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $definition */
        $definition->bindArgumentValue(0, 65);
    }

    public function testMethodDefinitionBindings_bindArgumentValueWithNamedArgumentIndex()
    {
        /** @var \Wootook\Core\DependencyInjection\Definition\ClassDefinition $classDefinition */
        $classDefinition = $this->getClassDefinitionMock();

        $definition = $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\MethodDefinition',
            array('getArgumentDefinition'), array($classDefinition, 'methodToTest'));

        $argumentDefinition = $this->getArgumentDefinitionMock(array('bindValue'));

        $definition->expects($this->once())
            ->method('getArgumentDefinition')
            ->with('fooArgumentOne', true)
            ->will($this->returnValue($argumentDefinition))
        ;

        $argumentDefinition->expects($this->once())
            ->method('bindValue')
            ->with(65)
        ;

        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $definition */
        $definition->bindArgumentValue('fooArgumentOne', 65);
    }

    public function testMethodDefinitionBindings_bindArgumentVariableWithNumericArgumentIndex()
    {
        /** @var \Wootook\Core\DependencyInjection\Definition\ClassDefinition $classDefinition */
        $classDefinition = $this->getClassDefinitionMock();

        $definition = $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\MethodDefinition',
            array('getArgumentDefinition'), array($classDefinition, 'methodToTest'));

        $argumentDefinition = $this->getArgumentDefinitionMock(array('bindVariable'));

        $definition->expects($this->once())
            ->method('getArgumentDefinition')
            ->with(0, true)
            ->will($this->returnValue($argumentDefinition))
        ;

        $argumentDefinition->expects($this->once())
            ->method('bindVariable')
            ->with(42)
        ;

        $variable = 42;
        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $definition */
        $definition->bindArgumentVariable(0, $variable);
    }

    public function testMethodDefinitionBindings_bindArgumentVariableWithNamedArgumentIndex()
    {
        /** @var \Wootook\Core\DependencyInjection\Definition\ClassDefinition $classDefinition */
        $classDefinition = $this->getClassDefinitionMock();

        $definition = $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\MethodDefinition',
            array('getArgumentDefinition'), array($classDefinition, 'methodToTest'));

        $argumentDefinition = $this->getArgumentDefinitionMock(array('bindVariable'));

        $definition->expects($this->once())
            ->method('getArgumentDefinition')
            ->with('fooArgumentOne', true)
            ->will($this->returnValue($argumentDefinition))
        ;

        $argumentDefinition->expects($this->once())
            ->method('bindVariable')
            ->with(42)
        ;

        $variable = 42;
        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $definition */
        $definition->bindArgumentVariable('fooArgumentOne', $variable);
    }

    public function testMethodDefinitionBindings_bindArgumentRegistryEntryWithNumericArgumentIndex()
    {
        /** @var \Wootook\Core\DependencyInjection\Definition\ClassDefinition $classDefinition */
        $classDefinition = $this->getClassDefinitionMock();
        /** @var \Wootook\Core\DependencyInjection\Registry $registry */
        $registry = $this->getRegistryMock();

        $definition = $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\MethodDefinition',
            array('getArgumentDefinition'), array($classDefinition, 'methodToTest', $registry));

        $argumentDefinition = $this->getArgumentDefinitionMock(array('bindRegistryEntry'));

        $definition->expects($this->once())
            ->method('getArgumentDefinition')
            ->with(0, true)
            ->will($this->returnValue($argumentDefinition))
        ;

        $argumentDefinition->expects($this->once())
            ->method('bindRegistryEntry')
            ->with('foo')
        ;

        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $definition */
        $definition->bindArgumentRegistryEntry(0, 'foo');
    }

    public function testMethodDefinitionBindings_bindArgumentRegistryEntryWithNamedArgumentIndex()
    {
        /** @var \Wootook\Core\DependencyInjection\Definition\ClassDefinition $classDefinition */
        $classDefinition = $this->getClassDefinitionMock();
        /** @var \Wootook\Core\DependencyInjection\Registry $registry */
        $registry = $this->getRegistryMock();

        $definition = $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\MethodDefinition',
            array('getArgumentDefinition'), array($classDefinition, 'methodToTest', $registry));

        $argumentDefinition = $this->getArgumentDefinitionMock(array('bindRegistryEntry'));

        $definition->expects($this->once())
            ->method('getArgumentDefinition')
            ->with('fooArgumentOne', true)
            ->will($this->returnValue($argumentDefinition))
        ;

        $argumentDefinition->expects($this->once())
            ->method('bindRegistryEntry')
            ->with('foo')
        ;

        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $definition */
        $definition->bindArgumentRegistryEntry('fooArgumentOne', 'foo');
    }

    public function testMethodDefinitionBindings_bindArgumentRegistryEntryWithNoRegistry()
    {
        /** @var \Wootook\Core\DependencyInjection\Definition\ClassDefinition $classDefinition */
        $classDefinition = $this->getClassDefinitionMock();

        $definition = $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\MethodDefinition',
            array('getArgumentDefinition'), array($classDefinition, 'methodToTest'));

        $argumentDefinition = $this->getArgumentDefinitionMock(array('bindRegistryEntry'));

        $definition->expects($this->once())
            ->method('getArgumentDefinition')
            ->with('fooArgumentOne', true)
            ->will($this->returnValue($argumentDefinition))
        ;

        $argumentDefinition->expects($this->once())
            ->method('bindRegistryEntry')
            ->with('foo')
            ->will($this->throwException(new \Wootook\Core\Exception\DependencyInjection\RuntimeException))
        ;
        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\RuntimeException');

        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $definition */
        $definition->bindArgumentRegistryEntry('fooArgumentOne', 'foo');
    }

    public function testMethodDefinition_getArgumentDefinitionWithInvalidArgumentId()
    {
        /** @var \Wootook\Core\DependencyInjection\Definition\ClassDefinition $classDefinition */
        $classDefinition = $this->getClassDefinitionMock();

        $definition = new Definition\MethodDefinition($classDefinition, 'methodToTest');

        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\InvalidArgumentException');

        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $definition */
        $definition->getArgumentDefinition(1.5);
    }

    public function testMethodDefinition_getArgumentReflector_withNumericArgumentId()
    {
        /** @var \Wootook\Core\DependencyInjection\Definition\ClassDefinition $classDefinition */
        $classDefinition = $this->getClassDefinitionMock();

        $definition = new Definition\MethodDefinition($classDefinition, 'methodToTest');

        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $definition */
        $reflector = $definition->getArgumentReflector(0);
        $this->assertInstanceOf('ReflectionParameter', $reflector);
        $this->assertEquals(0, $reflector->getPosition());
    }

    public function testMethodDefinition_getArgumentReflector_withInexistentNumericArgumentId()
    {
        /** @var \Wootook\Core\DependencyInjection\Definition\ClassDefinition $classDefinition */
        $classDefinition = $this->getClassDefinitionMock();

        $definition = new Definition\MethodDefinition($classDefinition, 'methodToTest');

        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\BadMethodCallException');

        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $definition */
        $definition->getArgumentReflector(5);
    }

    public function testMethodDefinition_getArgumentReflector_withNamedArgumentId()
    {
        /** @var \Wootook\Core\DependencyInjection\Definition\ClassDefinition $classDefinition */
        $classDefinition = $this->getClassDefinitionMock();

        $definition = new Definition\MethodDefinition($classDefinition, 'methodToTest');

        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $definition */
        $reflector = $definition->getArgumentReflector('fooArgumentOne');
        $this->assertInstanceOf('ReflectionParameter', $reflector);
        $this->assertEquals(0, $reflector->getPosition());
    }

    public function testMethodDefinition_getArgumentReflector_withInexistentNamedArgumentId()
    {
        /** @var \Wootook\Core\DependencyInjection\Definition\ClassDefinition $classDefinition */
        $classDefinition = $this->getClassDefinitionMock();

        $definition = new Definition\MethodDefinition($classDefinition, 'methodToTest');

        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\BadMethodCallException');

        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $definition */
        $definition->getArgumentReflector('inexistentArgument');
    }

    public function testMethodDefinition_getArgumentReflector_withInvalidArgumentId()
    {
        /** @var \Wootook\Core\DependencyInjection\Definition\ClassDefinition $classDefinition */
        $classDefinition = $this->getClassDefinitionMock();

        $definition = new Definition\MethodDefinition($classDefinition, 'methodToTest');

        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\BadMethodCallException');

        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $definition */
        $definition->getArgumentReflector(1.5);
    }

    public function testMethodDefinition_getArgumentDefinition_withNumericArgumentId()
    {
        /** @var \Wootook\Core\DependencyInjection\Definition\ClassDefinition $classDefinition */
        $classDefinition = $this->getClassDefinitionMock();

        $definition = new Definition\MethodDefinition($classDefinition, 'methodToTest');

        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $definition */
        $argumentDefinition = $definition->getArgumentDefinition(0);
        $this->assertInstanceOf('Wootook\\Core\\DependencyInjection\\Definition\\ArgumentDefinition', $argumentDefinition);
    }

    public function testMethodDefinition_getArgumentDefinition_withInexistentNumericArgumentId()
    {
        /** @var \Wootook\Core\DependencyInjection\Definition\ClassDefinition $classDefinition */
        $classDefinition = $this->getClassDefinitionMock();

        $definition = new Definition\MethodDefinition($classDefinition, 'methodToTest');

        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\BadMethodCallException');

        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $definition */
        $definition->getArgumentDefinition(5);
    }

    public function testMethodDefinition_getArgumentDefinition_withNamedArgumentId()
    {
        /** @var \Wootook\Core\DependencyInjection\Definition\ClassDefinition $classDefinition */
        $classDefinition = $this->getClassDefinitionMock();

        $definition = new Definition\MethodDefinition($classDefinition, 'methodToTest');

        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $definition */
        $argumentDefinition = $definition->getArgumentDefinition('fooArgumentOne');
        $this->assertInstanceOf('Wootook\\Core\\DependencyInjection\\Definition\\ArgumentDefinition', $argumentDefinition);
    }

    public function testMethodDefinition_getArgumentDefinition_withInexistentNamedArgumentId()
    {
        /** @var \Wootook\Core\DependencyInjection\Definition\ClassDefinition $classDefinition */
        $classDefinition = $this->getClassDefinitionMock();

        $definition = new Definition\MethodDefinition($classDefinition, 'methodToTest');

        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\InvalidArgumentException');

        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $definition */
        $definition->getArgumentDefinition('inexistentArgument');
    }

    public function testMethodDefinition_getArgumentDefinition_withInvalidArgumentId()
    {
        /** @var \Wootook\Core\DependencyInjection\Definition\ClassDefinition $classDefinition */
        $classDefinition = $this->getClassDefinitionMock();

        $definition = new Definition\MethodDefinition($classDefinition, 'methodToTest');

        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\InvalidArgumentException');

        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $definition */
        $definition->getArgumentDefinition(1.5);
    }

    public function testMethodDefinition_getArgumentDefinition_withExistingArgument()
    {
        /** @var \Wootook\Core\DependencyInjection\Definition\ClassDefinition $classDefinition */
        $classDefinition = $this->getClassDefinitionMock();

        $definition = new Definition\MethodDefinition($classDefinition, 'methodToTest');

        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $definition */
        $definition->addArgumentDefinition(0);

        $argumentDefinition = $definition->getArgumentDefinition(0);
        $this->assertInstanceOf('Wootook\\Core\\DependencyInjection\\Definition\\ArgumentDefinition', $argumentDefinition);
    }

    public function testMethodDefinition_getArgumentDefinition_withInexistingArgumentRegistered()
    {
        /** @var \Wootook\Core\DependencyInjection\Definition\ClassDefinition $classDefinition */
        $classDefinition = $this->getClassDefinitionMock();

        $definition = new Definition\MethodDefinition($classDefinition, 'methodToTest');

        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $definition */
        $argumentDefinition = $definition->getArgumentDefinition(0, true);
        $this->assertInstanceOf('Wootook\\Core\\DependencyInjection\\Definition\\ArgumentDefinition', $argumentDefinition);
    }

    public function testMethodDefinition_resetArgumentDefinitionList()
    {
        /** @var \Wootook\Core\DependencyInjection\Definition\ClassDefinition $classDefinition */
        $classDefinition = $this->getClassDefinitionMock();

        $definition = new Definition\MethodDefinition($classDefinition, 'methodToTest');

        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $definition */
        $definition->getArgumentDefinition(0, true);
        $definition->getArgumentDefinition(1, true);

        $all = $definition->getAllArgumentDefinitions();
        $this->assertInternalType('array', $all);
        $this->assertCount(2, $all);

        $definition->reset();
        $all = $definition->getAllArgumentDefinitions();
        $this->assertInternalType('array', $all);
        $this->assertCount(0, $all);
    }
}
