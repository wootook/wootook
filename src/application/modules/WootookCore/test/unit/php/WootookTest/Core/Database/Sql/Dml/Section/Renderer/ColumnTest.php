<?php

namespace WootookTest\Core\Database\Sql\Dml\Section\Renderer;

include __DIR__ . '/ColumnMock.php';

class ColumnTest
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

        $this->_queryMock = $this->getMockForAbstractClass('WootookTest\\Core\\Database\\Sql\\Dml\\Section\\ColumnMock', array($adapterMock));
        $this->_rendererMock = $this->getMockForAbstractClass('WootookTest\\Core\\Database\\Sql\\Dml\\Section\\Renderer\\ColumnMock');
    }

    public function testRenderColumnWithoutTableWithoutAlias()
    {
        $this->_queryMock->column('foo_column');

        $this->assertRegExp('#\s*[a-zA-Z_][a-zA-Z0-9_]+\s*#', $this->_rendererMock->renderColumn($this->_queryMock));
    }

    public function testRenderColumnWithTableWithoutAlias()
    {
        $this->_queryMock->column('foo_column', 'testing_table');

        $this->assertRegExp('#\s*[a-zA-Z_][a-zA-Z0-9_]+\.[a-zA-Z_][a-zA-Z0-9_]+\s*#', $this->_rendererMock->renderColumn($this->_queryMock));
    }

    public function testRenderOneTableWithoutTableWithAlias()
    {
        $this->_queryMock->column(array('my_alias' => 'foo_column'));

        $this->assertRegExp('#\s*[a-zA-Z_][a-zA-Z0-9_]+\s+AS\s+[a-zA-Z_][a-zA-Z0-9_]+\s*#', $this->_rendererMock->renderColumn($this->_queryMock));
    }

    public function testRenderColumnWithTableWithAlias()
    {
        $this->_queryMock->column(array('my_alias' => 'foo_column'), 'testing_table');

        $this->assertRegExp('#\s*[a-zA-Z_][a-zA-Z0-9_]+\.[a-zA-Z_][a-zA-Z0-9_]+\s+AS\s+[a-zA-Z_][a-zA-Z0-9_]+\s*#', $this->_rendererMock->renderColumn($this->_queryMock));
    }
}
