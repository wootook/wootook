<?php

namespace WootookUnit\Core\DependencyInjection\Definition;

use Wootook\Core\Config,
    Wootook\Core\DependencyInjection\Definition;

class ClassDefinitionTest extends \PHPUnit_Framework_TestCase
{
    public function methodToTest($fooArgumentOne, $fooArgumentTwo = null)
    {
        // This method exists only for testing method introspection with the Dependency Injection component
    }

    /**
     * @return \Wootook\Core\DependencyInjection\Definition\ArgumentDefinition
     */
    public function getMethodDefinitionMock(Array $additionalMethods = array())
    {
        $argumentDefinition = $this->getMockBuilder('Wootook\\Core\\DependencyInjection\\Definition\\MethodDefinition')
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

    public function testClassDefinitionInstantiation_withNonExistingClass()
    {
        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\BadMethodCallException');
        new Definition\ClassDefinition('InexistentClass');
    }

    public function testClassDefinitionInstantiation_withExistingClass()
    {
        $definition = new Definition\ClassDefinition('stdClass');

        $this->assertEquals('stdClass', $definition->getReflector()->getName());
    }

    public function testClassDefinitionInstantiation_withExistingClassWithRegistry()
    {
        /** @var \Wootook\Core\DependencyInjection\Registry $registry */
        $registry = $this->getRegistryMock();

        $definition = new Definition\ClassDefinition('stdClass', $registry);

        $this->assertEquals('stdClass', $definition->getReflector()->getName());
        $this->assertSame($registry, $definition->getRegistry());
    }

    public function testClassDefinitionInstantiation_withExistingClass_withRegistry_withSpecialMethodDefinitionClassName()
    {
        /** @var \Wootook\Core\DependencyInjection\Registry $registry */
        $registry = $this->getRegistryMock();

        $definition = new Definition\ClassDefinition('stdClass', $registry, 'stdClass');

        $this->assertEquals('stdClass', $definition->getMethodDefinitionClassName());
        $this->assertSame($registry, $definition->getRegistry());
    }

    public function testClassDefinitionInstantiation_withInvalidSpecialArgumentDefinitionClassName()
    {
        /** @var \Wootook\Core\DependencyInjection\Registry $registry */
        $registry = $this->getRegistryMock();

        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\InvalidArgumentException');
        $definition = new Definition\ClassDefinition('stdClass', $registry, 32);
    }

    public function testClassDefinitionInstantiation_validateReflectorInstance()
    {
        $definition = new Definition\ClassDefinition('stdClass');

        $this->assertInstanceOf('ReflectionClass', $definition->getReflector());
        $this->assertEquals('stdClass', $definition->getReflector()->getName());
    }

    public function testClassDefinitionAccessorsAndMutators_usingReflector()
    {
        $definition = new Definition\classDefinition('stdClass');

        $this->assertInstanceOf('ReflectionClass', $definition->getReflector());

        $reflector = new \Reflectionclass(__CLASS__);
        $definition->setReflector($reflector);

        $this->assertSame($reflector, $definition->getReflector());
    }

    public function testClassDefinitionMethodDefinitions_initMethodDefinition()
    {
        $definition = new Definition\ClassDefinition(__CLASS__, null, 'stdClass');

        $methodDefinition = $definition->initMethodDefinition('methodToTest');

        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\InvalidArgumentException');
        $definition->initMethodDefinition(0);
    }

    public function testClassDefinitionMethodDefinitions_registerMethodDefinition()
    {
        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $methodDefinition */
        $methodDefinition = $this->getMethodDefinitionMock();

        $definition = new Definition\ClassDefinition('stdClass');

        $definition->registerMethodDefinition('methodToTest', $methodDefinition);
        $this->assertCount(1, $definition->getAllMethodDefinitions());

        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\InvalidArgumentException');
        $definition->registerMethodDefinition(array(), $methodDefinition);
    }

    public function testClassDefinitionMethodDefinitions_addMethodDefinition()
    {
        /** @var \Wootook\Core\DependencyInjection\Definition\ArgumentDefinition $argumentDefinition */
        $methodDefinition = $this->getMethodDefinitionMock(array('__construct'));

        $definition = new Definition\ClassDefinition(__CLASS__, null, get_class($methodDefinition));

        $definition->addMethodDefinition('methodToTest');
        $this->assertCount(1, $definition->getAllMethodDefinitions());
        $this->assertInstanceOf(get_class($methodDefinition), $definition->getAllMethodDefinitions()['methodToTest']);
    }

    public function testClassDefinitionMethodDefinitions_setMethodDefinition()
    {
        /** @var \Wootook\Core\DependencyInjection\Definition\ArgumentDefinition $argumentDefinition */
        $methodDefinition = $this->getMethodDefinitionMock();

        $definition = new Definition\ClassDefinition(__CLASS__, null, 'stdClass');

        $definition->setMethodDefinition('methodToTest', $methodDefinition);
        $this->assertCount(1, $definition->getAllMethodDefinitions());
        $this->assertInstanceOf(get_class($methodDefinition), $definition->getAllMethodDefinitions()['methodToTest']);

        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\InvalidArgumentException');
        $definition->setMethodDefinition(0, $methodDefinition);
    }

    public function testClassDefinition_getMethodDefinitionWithInvalidMethodName()
    {
        $definition = new Definition\ClassDefinition(__CLASS__);

        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\InvalidArgumentException');

        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $definition */
        $definition->getMethodDefinition(1.5);
    }

    public function testMethodDefinition_getArgumentReflector_withInexistentMethodName()
    {
        $definition = new Definition\ClassDefinition(__CLASS__);

        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\BadMethodCallException');

        /** @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition $definition */
        $definition->getMethodDefinition('inexistentMethod');
    }

    public function testGetAllMethodDefinitions()
    {
        $mock = $this->getMethodDefinitionMock();

        $definition = new Definition\ClassDefinition(__CLASS__);
        $definition->setMethodDefinition('fooMethod', $mock);

        $expected = array(
            'fooMethod' => $mock
        );
        $this->assertEquals($expected, $definition->getAllMethodDefinitions());
    }

    public function testReset()
    {
        $mock = $this->getMethodDefinitionMock();

        $definition = new Definition\ClassDefinition(__CLASS__);
        $definition->setMethodDefinition('fooMethod', $mock);

        $definition->reset();

        $this->assertEquals(array(), $definition->getAllMethodDefinitions());
    }


//    public function testClassDefinitionInstantiation_withSpecialMethodDefinitionClassName()
//    {
//        $methodDefinitionClassName = \uniqid('WootookUnit_Core_DependencyInjection_Mock_MethodDefinition_TestNewInstance_');
//        $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\MethodDefinition', array(), array(), $methodDefinitionClassName, false);
//
//        $definition = new Definition\ClassDefinition(__CLASS__, null, $methodDefinitionClassName);
//
//        $this->assertInstanceOf($methodDefinitionClassName, $definition->getMethodDefinition(__FUNCTION__));
//    }

//    public function testClassDefinitionInstantiation_assignNewMethodDefinitionInstance()
//    {
//        $mock = $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\MethodDefinition', array(), array(), '', false);
//
//        $definition = new Definition\ClassDefinition(__CLASS__);
//        $definition->setMethodDefinition(__FUNCTION__, $mock);
//
//        $this->assertSame($mock, $definition->getMethodDefinition(__FUNCTION__));
//    }
//
//    public function testGetMethodDefinition_withInvalidMethodName()
//    {
//        $definition = new Definition\ClassDefinition(__CLASS__);
//
//        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\InvalidArgumentException');
//        $definition->getMethodDefinition(42);
//    }
//
//    public function testSetMethodDefinition_withInvalidMethodName()
//    {
//        $mock = $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\MethodDefinition', array(), array(), '', false);
//
//        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\InvalidArgumentException');
//
//        $definition = new Definition\ClassDefinition(__CLASS__);
//        $definition->setMethodDefinition(42, $mock);
//    }
//
//    public function testClassDefinitionAccessors_getReflector()
//    {
//        $definition = new Definition\ClassDefinition(__CLASS__);
//
//        $this->assertInstanceOf('\\ReflectionClass', $definition->getReflector());
//    }
//
//    public function testClassDefinitionMutators_setReflector()
//    {
//        $definition = new Definition\ClassDefinition(__CLASS__);
//        $reflector = new \ReflectionClass(__CLASS__);
//        $definition->setReflector($reflector);
//
//        $this->assertSame($reflector, $definition->getReflector());
//    }
//
//    public function testNewInstance_hasNoConstructor()
//    {
//        $this->markTestSkipped("Method ReflectionClass::newInstance() couldn't be mocked due to API<->Reflection inconsistencies.");
//
//        $reflectionClassMock = $this->getMock('ReflectionClass', array('hasMethod', 'newInstance'), array('stdClass'));
//        $reflectionClassMock->expects($this->once())
//            ->method('hasMethod')
//            ->with('__construct')
//            ->will($this->returnValue(false))
//        ;
//        $reflectionClassMock->expects($this->once())
//            ->method('newInstance')
//            ->will($this->returnValue(new \stdClass()))
//        ;
//
//        $classDefinition = new Definition\ClassDefinition(__CLASS__);
//        $classDefinition->setReflector($reflectionClassMock);
//
//        $this->assertInstanceOf('stdClass', $classDefinition->newInstance());
//    }
//
//    public function testNewInstance_usingConstructor()
//    {
//        $expectedArguments = array(42);
//
//        $reflectionClassMock = $this->getMock('ReflectionClass', array('hasMethod', 'newInstanceArgs'), array('stdClass'));
//        $reflectionClassMock->expects($this->once())
//            ->method('hasMethod')
//            ->with('__construct')
//            ->will($this->returnValue(true))
//        ;
//        $reflectionClassMock->expects($this->once())
//            ->method('newInstanceArgs')
//            ->with($expectedArguments)
//            ->will($this->returnValue(new \stdClass()))
//        ;
//
//        $classDefinition = new Definition\ClassDefinition(__CLASS__);
//        $classDefinition->setReflector($reflectionClassMock);
//
//        $methodDefinitionMock = $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\MethodDefinition', array('compileArgs'), array(), '', false);
//        $methodDefinitionMock->expects($this->once())
//            ->method('compileArgs')
//            ->will($this->returnValue(array(42)))
//        ;
//        $classDefinition->setMethodDefinition('__construct', $methodDefinitionMock);
//
//        $this->assertInstanceOf('stdClass', $classDefinition->newInstance());
//    }
//
//    public function testNewInstance_usingNonPublicConstructor()
//    {
//        $reflectionClassMock = $this->getMock('ReflectionClass', array('hasMethod', 'newInstanceArgs'), array('stdClass'));
//        $reflectionClassMock->expects($this->once())
//            ->method('hasMethod')
//            ->with('__construct')
//            ->will($this->returnValue(true))
//        ;
//        $reflectionClassMock->expects($this->once())
//            ->method('newInstanceArgs')
//            ->will($this->throwException($this->getMock('ReflectionException')))
//        ;
//
//        $classDefinition = new Definition\ClassDefinition(__CLASS__);
//        $classDefinition->setReflector($reflectionClassMock);
//
//        $methodDefinitionMock = $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\MethodDefinition', array('compileArgs'), array(), '', false);
//        $methodDefinitionMock->expects($this->once())
//            ->method('compileArgs')
//            ->will($this->returnValue(array(42)))
//        ;
//        $classDefinition->setMethodDefinition('__construct', $methodDefinitionMock);
//
//        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\RuntimeException');
//
//        $this->assertInstanceOf('stdClass', $classDefinition->newInstance());
//    }
//
//    public function testNewInstanceWithoutConstructor()
//    {
//        $reflectionClassMock = $this->getMock('ReflectionClass', array('newInstanceWithoutConstructor'), array('stdClass'));
//        $reflectionClassMock->expects($this->once())
//            ->method('newInstanceWithoutConstructor')
//            ->will($this->returnValue(new \stdClass()))
//        ;
//
//        $classDefinition = new Definition\ClassDefinition(__CLASS__);
//        $classDefinition->setReflector($reflectionClassMock);
//
//        $this->assertInstanceOf('stdClass', $classDefinition->newInstanceWithoutConstructor());
//    }
//
//    public function testNewInstanceWithoutConstructor_throwsException()
//    {
//        $reflectionClassMock = $this->getMock('ReflectionClass', array('newInstanceWithoutConstructor'), array('stdClass'));
//        $reflectionClassMock->expects($this->once())
//            ->method('newInstanceWithoutConstructor')
//            ->will($this->throwException($this->getMock('ReflectionException')))
//        ;
//
//        $classDefinition = new Definition\ClassDefinition(__CLASS__);
//        $classDefinition->setReflector($reflectionClassMock);
//
//        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\RuntimeException');
//
//        $this->assertInstanceOf('stdClass', $classDefinition->newInstanceWithoutConstructor());
//    }
}
