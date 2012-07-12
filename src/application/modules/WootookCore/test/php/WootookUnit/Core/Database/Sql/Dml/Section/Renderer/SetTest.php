<?php

namespace WootookUnit\Core\Database\Sql\Dml\Section\Renderer;

include __DIR__ . '/SetMock.php';

class SetTest
    extends \PHPUnit_Framework_TestCase
{
    protected $_queryMock = null;
    protected $_rendererMock = null;

    public function setUp()
    {
        $adapterMock = $this->getMockForAbstractClass('Wootook\\Core\\Database\\Adapter\\Adapter');

        $adapterMock->expects($this->any())
            ->method('quoteIdentifier')
            ->will($this->returnArgument(0))
        ;

        $adapterMock->expects($this->any())
            ->method('quote')
            ->will($this->returnArgument(0))
        ;

        $this->_queryMock = $this->getMockForAbstractClass('WootookUnit\\Core\\Database\\Sql\\Dml\\Section\\SetMock', array($adapterMock));
        $this->_rendererMock = $this->getMockForAbstractClass('WootookUnit\\Core\\Database\\Sql\\Dml\\Section\\Renderer\\SetMock');
    }

    public function testRenderFieldAndValue()
    {
        $this->_queryMock->set('foo_field', 18);

        $this->assertRegExp('#\s*SET\s*foo_field=18\s*#', $this->_rendererMock->renderSet($this->_queryMock));
    }

    public function testRenderFieldAndValueWithPlaceholder()
    {
        $placeholder = $this->getMockForAbstractClass('Wootook\\Core\\Database\\Sql\\Placeholder\\Placeholder');

        $placeholder->expects($this->any())
            ->method('__toString')
            ->will($this->returnValue('18'))
        ;

        $this->_queryMock->set('foo_field', $placeholder);

        $this->assertRegExp('#\s*SET\s*foo_field=18\s*#', $this->_rendererMock->renderSet($this->_queryMock));
    }

    public function testRenderMultipleFieldAndValueAsArray()
    {
        $this->_queryMock->set(array(
            'id'        => 18,
            'foo_field' => 'test',
            'example'   => null
        ));

        $this->assertRegExp('#\s*SET\s+[a-zA-Z_][a-zA-Z0-9_]*\s*=\s*[0-9]+\s*,\s*[a-zA-Z_][a-zA-Z0-9_]*\s*=\s*[a-zA-Z]+\s*,\s*[a-zA-Z_][a-zA-Z0-9_]*\s*=\s*NULL\s*#', $this->_rendererMock->renderSet($this->_queryMock));
    }

    public function testRenderNoField()
    {
        $this->assertEmpty($this->_rendererMock->renderSet($this->_queryMock));
    }
}
