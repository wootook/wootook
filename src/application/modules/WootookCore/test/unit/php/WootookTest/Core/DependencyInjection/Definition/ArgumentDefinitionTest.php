<?php

namespace WootookTest\Core\DependencyInjection\Definition;

use Wootook\Core\DependencyInjection,
    Wootook\Core\DependencyInjection\Definition;

class ArgumentDefinitionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Wootook\Core\DependencyInjection\Definition\MethodDefinition
     */
    protected $_methodDefinitionMock = null;

    public function myTestingMethod($param1, $param2 = null)
    {
        return $param1;
    }

    public function setUp()
    {
        $this->_methodDefinitionMock = $this->getMock('Wootook\\Core\\DependencyInjection\\Definition\\MethodDefinition',
                array(), array(), '', false);
    }

    public function testBuildNewWithoutRegistryWithParameterId_bindValue()
    {
        $reflectionParameter = new \ReflectionParameter(array(__CLASS__, 'myTestingMethod'), 0);

        $this->_methodDefinitionMock->expects($this->any())
            ->method('getArgumentReflector')
            ->with(0)
            ->will($this->returnValue($reflectionParameter))
        ;

        $definition = new Definition\ArgumentDefinition($this->_methodDefinitionMock, 0);

        $definition->bindValue(36);

        $compiledArguments = array();
        $definition->compileInto($compiledArguments);

        $this->assertEquals(array(36), $compiledArguments);
    }

    public function testBuildNewWithoutRegistryWithParameterName_bindValue()
    {
        $reflectionParameter = new \ReflectionParameter(array(__CLASS__, 'myTestingMethod'), 0);

        $this->_methodDefinitionMock->expects($this->any())
            ->method('getArgumentReflector')
            ->with('param1')
            ->will($this->returnValue($reflectionParameter))
        ;

        $definition = new Definition\ArgumentDefinition($this->_methodDefinitionMock, 'param1');

        $definition->bindValue(36);

        $compiledArguments = array();
        $definition->compileInto($compiledArguments);

        $this->assertEquals(array(36), $compiledArguments);
    }

    public function testBuildNewWithRegistryWithParameterId_bindValue()
    {
        $reflectionParameter = new \ReflectionParameter(array(__CLASS__, 'myTestingMethod'), 0);

        $this->_methodDefinitionMock->expects($this->any())
            ->method('getArgumentReflector')
            ->with(0)
            ->will($this->returnValue($reflectionParameter))
        ;

        $registry = $this->getMock('DependencyInjection\Registry');
        $definition = new Definition\ArgumentDefinition($this->_methodDefinitionMock, 0);

        $definition->bindValue(36);

        $compiledArguments = array();
        $definition->compileInto($compiledArguments);

        $this->assertEquals(array(36), $compiledArguments);
    }

    public function testBuildNewWithRegistryWithParameterName_bindValue()
    {
        $reflectionParameter = new \ReflectionParameter(array(__CLASS__, 'myTestingMethod'), 0);

        $this->_methodDefinitionMock->expects($this->any())
            ->method('getArgumentReflector')
            ->with('param1')
            ->will($this->returnValue($reflectionParameter))
        ;

        $definition = new Definition\ArgumentDefinition($this->_methodDefinitionMock, 'param1');

        $definition->bindValue(36);

        $compiledArguments = array();
        $definition->compileInto($compiledArguments);

        $this->assertEquals(array(36), $compiledArguments);
    }

    public function testBuildNewWithoutRegistryWithParameterId_bindVariable()
    {
        $reflectionParameter = new \ReflectionParameter(array(__CLASS__, 'myTestingMethod'), 0);

        $this->_methodDefinitionMock->expects($this->any())
            ->method('getArgumentReflector')
            ->with(0)
            ->will($this->returnValue($reflectionParameter))
        ;

        $definition = new Definition\ArgumentDefinition($this->_methodDefinitionMock, 0);

        $var = 36;
        $definition->bindVariable($var);

        $compiledArguments = array();
        $definition->compileInto($compiledArguments);

        $this->assertEquals(array(&$var), $compiledArguments);
    }

    public function testBuildNewWithoutRegistryWithParameterName_bindVariable()
    {
        $reflectionParameter = new \ReflectionParameter(array(__CLASS__, 'myTestingMethod'), 0);

        $this->_methodDefinitionMock->expects($this->any())
            ->method('getArgumentReflector')
            ->with('param1')
            ->will($this->returnValue($reflectionParameter))
        ;

        $definition = new Definition\ArgumentDefinition($this->_methodDefinitionMock, 'param1');

        $var = 36;
        $definition->bindVariable($var);

        $compiledArguments = array();
        $definition->compileInto($compiledArguments);

        $this->assertEquals(array(&$var), $compiledArguments);
    }

    public function testBuildNewWithoutRegistryWithParameterId_bindRegistryEntry()
    {
        $reflectionParameter = new \ReflectionParameter(array(__CLASS__, 'myTestingMethod'), 0);

        $this->_methodDefinitionMock->expects($this->any())
            ->method('getArgumentReflector')
            ->with(0)
            ->will($this->returnValue($reflectionParameter))
        ;

        $definition = new Definition\ArgumentDefinition($this->_methodDefinitionMock, 0);

        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\RuntimeException');
        $definition->bindRegistryEntry('testing');

        $compiledArguments = array();
        $definition->compileInto($compiledArguments);
    }

    public function testBuildNewWithoutRegistryWithParameterName_bindRegistryEntry()
    {
        $reflectionParameter = new \ReflectionParameter(array(__CLASS__, 'myTestingMethod'), 0);

        $this->_methodDefinitionMock->expects($this->any())
            ->method('getArgumentReflector')
            ->with('param1')
            ->will($this->returnValue($reflectionParameter))
        ;

        $definition = new Definition\ArgumentDefinition($this->_methodDefinitionMock, 'param1');

        $this->setExpectedException('Wootook\\Core\\Exception\\DependencyInjection\\RuntimeException');
        $definition->bindRegistryEntry('testing');

        $compiledArguments = array();
        $definition->compileInto($compiledArguments);
    }
}
