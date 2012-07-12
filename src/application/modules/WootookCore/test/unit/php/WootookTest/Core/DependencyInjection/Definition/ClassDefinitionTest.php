<?php

namespace WootookTest\Core\DependencyInjection\Definition;

use Wootook\Core\Config,
    Wootook\Core\DependencyInjection\Definition;

class ClassDefinitionTest extends \PHPUnit_Framework_TestCase
{
    public function testClassDefinitionInstantiation_withNonExistingClass()
    {
        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\BadMethodCallException');
        new Definition\ClassDefinition('InexistentClass');
    }

    public function testClassDefinitionInstantiation_withSpecialMethodDefinitionClassName()
    {
        $methodDefinitionClassName = \uniqid('WootookTest_Core_DependencyInjection_Mock_MethodDefinition_TestNewInstance_');
        $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\MethodDefinition', array(), array(), $methodDefinitionClassName, false);

        $definition = new Definition\ClassDefinition(__CLASS__, $methodDefinitionClassName);

        $this->assertInstanceOf($methodDefinitionClassName, $definition->getMethodDefinition(__FUNCTION__));
    }

    public function testClassDefinitionInstantiation_assignNewMethodDefinitionInstance()
    {
        $mock = $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\MethodDefinition', array(), array(), '', false);

        $definition = new Definition\ClassDefinition(__CLASS__);
        $definition->setMethodDefinition(__FUNCTION__, $mock);

        $this->assertSame($mock, $definition->getMethodDefinition(__FUNCTION__));
    }

    public function testGetMethodDefinition_withInvalidMethodName()
    {
        $definition = new Definition\ClassDefinition(__CLASS__);

        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\InvalidArgumentException');
        $definition->getMethodDefinition(42);
    }

    public function testSetMethodDefinition_withInvalidMethodName()
    {
        $mock = $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\MethodDefinition', array(), array(), '', false);

        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\InvalidArgumentException');

        $definition = new Definition\ClassDefinition(__CLASS__);
        $definition->setMethodDefinition(42, $mock);
    }

    public function testGetAllMethodDefinitions()
    {
        $mock = $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\MethodDefinition', array(), array(), '', false);

        $definition = new Definition\ClassDefinition(__CLASS__);
        $definition->setMethodDefinition('fooMethod', $mock);

        $expected = array(
            'fooMethod' => $mock
        );
        $this->assertEquals($expected, $definition->getAllMethodDefinitions());
    }

    public function testReset()
    {
        $mock = $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\MethodDefinition', array(), array(), '', false);

        $definition = new Definition\ClassDefinition(__CLASS__);
        $definition->setMethodDefinition('fooMethod', $mock);

        $definition->reset();

        $this->assertEquals(array(), $definition->getAllMethodDefinitions());
    }

    public function testClassDefinitionAccessors_getReflector()
    {
        $definition = new Definition\ClassDefinition(__CLASS__);

        $this->assertInstanceOf('\\ReflectionClass', $definition->getReflector());
    }

    public function testClassDefinitionMutators_setReflector()
    {
        $definition = new Definition\ClassDefinition(__CLASS__);
        $reflector = new \ReflectionClass(__CLASS__);
        $definition->setReflector($reflector);

        $this->assertSame($reflector, $definition->getReflector());
    }

    public function testNewInstance_hasNoConstructor()
    {
        $this->markTestSkipped("Method ReflectionClass::newInstance() couldn't be mocked due to API<->Reflection inconsistencies.");

        $reflectionClassMock = $this->getMock('ReflectionClass', array('hasMethod', 'newInstance'), array('stdClass'));
        $reflectionClassMock->expects($this->once())
            ->method('hasMethod')
            ->with('__construct')
            ->will($this->returnValue(false))
        ;
        $reflectionClassMock->expects($this->once())
            ->method('newInstance')
            ->will($this->returnValue(new \stdClass()))
        ;

        $classDefinition = new Definition\ClassDefinition(__CLASS__);
        $classDefinition->setReflector($reflectionClassMock);

        $this->assertInstanceOf('stdClass', $classDefinition->newInstance());
    }

    public function testNewInstance_usingConstructor()
    {
        $expectedArguments = array(42);

        $reflectionClassMock = $this->getMock('ReflectionClass', array('hasMethod', 'newInstanceArgs'), array('stdClass'));
        $reflectionClassMock->expects($this->once())
            ->method('hasMethod')
            ->with('__construct')
            ->will($this->returnValue(true))
        ;
        $reflectionClassMock->expects($this->once())
            ->method('newInstanceArgs')
            ->with($expectedArguments)
            ->will($this->returnValue(new \stdClass()))
        ;

        $classDefinition = new Definition\ClassDefinition(__CLASS__);
        $classDefinition->setReflector($reflectionClassMock);

        $methodDefinitionMock = $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\MethodDefinition', array('compileArgs'), array(), '', false);
        $methodDefinitionMock->expects($this->once())
            ->method('compileArgs')
            ->will($this->returnValue(array(42)))
        ;
        $classDefinition->setMethodDefinition('__construct', $methodDefinitionMock);

        $this->assertInstanceOf('stdClass', $classDefinition->newInstance());
    }

    public function testNewInstance_usingNonPublicConstructor()
    {
        $reflectionClassMock = $this->getMock('ReflectionClass', array('hasMethod', 'newInstanceArgs'), array('stdClass'));
        $reflectionClassMock->expects($this->once())
            ->method('hasMethod')
            ->with('__construct')
            ->will($this->returnValue(true))
        ;
        $reflectionClassMock->expects($this->once())
            ->method('newInstanceArgs')
            ->will($this->throwException($this->getMock('ReflectionException')))
        ;

        $classDefinition = new Definition\ClassDefinition(__CLASS__);
        $classDefinition->setReflector($reflectionClassMock);

        $methodDefinitionMock = $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\MethodDefinition', array('compileArgs'), array(), '', false);
        $methodDefinitionMock->expects($this->once())
            ->method('compileArgs')
            ->will($this->returnValue(array(42)))
        ;
        $classDefinition->setMethodDefinition('__construct', $methodDefinitionMock);

        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\RuntimeException');

        $this->assertInstanceOf('stdClass', $classDefinition->newInstance());
    }

    public function testNewInstanceWithoutConstructor()
    {
        $reflectionClassMock = $this->getMock('ReflectionClass', array('newInstanceWithoutConstructor'), array('stdClass'));
        $reflectionClassMock->expects($this->once())
            ->method('newInstanceWithoutConstructor')
            ->will($this->returnValue(new \stdClass()))
        ;

        $classDefinition = new Definition\ClassDefinition(__CLASS__);
        $classDefinition->setReflector($reflectionClassMock);

        $this->assertInstanceOf('stdClass', $classDefinition->newInstanceWithoutConstructor());
    }

    public function testNewInstanceWithoutConstructor_throwsException()
    {
        $reflectionClassMock = $this->getMock('ReflectionClass', array('newInstanceWithoutConstructor'), array('stdClass'));
        $reflectionClassMock->expects($this->once())
            ->method('newInstanceWithoutConstructor')
            ->will($this->throwException($this->getMock('ReflectionException')))
        ;

        $classDefinition = new Definition\ClassDefinition(__CLASS__);
        $classDefinition->setReflector($reflectionClassMock);

        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\RuntimeException');

        $this->assertInstanceOf('stdClass', $classDefinition->newInstanceWithoutConstructor());
    }
}
