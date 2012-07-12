<?php

namespace WootookTest\Core\Database\Sql\Dml\Section\Renderer;

include __DIR__ . '/IntoMock.php';

class IntoTest
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

        $this->_queryMock = $this->getMockForAbstractClass('WootookTest\\Core\\Database\\Sql\\Dml\\Section\\IntoMock', array($adapterMock));
        $this->_rendererMock = $this->getMockForAbstractClass('WootookTest\\Core\\Database\\Sql\\Dml\\Section\\Renderer\\IntoMock');
    }

    public function testRenderTableWithoutSchema()
    {
        $this->_queryMock->into('foo_table');

        $this->assertRegExp('#\s*foo_table\s*#', $this->_rendererMock->renderInto($this->_queryMock));
    }

    public function testRenderTableWithSchema()
    {
        $this->_queryMock->into('foo_table', 'testing_schema');

        $this->assertRegExp('#\s*testing_schema\.foo_table\s*#', $this->_rendererMock->renderInto($this->_queryMock));
    }
}
