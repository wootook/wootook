<?php

namespace WootookTest\Core\Database\Sql\Dml\Section\Renderer;

include __DIR__ . '/FromMock.php';

class FromTest
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

        $this->_queryMock = $this->getMockForAbstractClass('WootookTest\\Core\\Database\\Sql\\Dml\\Section\\FromMock', array($adapterMock));
        $this->_rendererMock = $this->getMockForAbstractClass('WootookTest\\Core\\Database\\Sql\\Dml\\Section\\Renderer\\FromMock');
    }

    public function testRenderOneTableWithoutSchemaWithoutAlias()
    {
        $this->_queryMock->from('foo_table');

        $this->assertRegExp('#\s*FROM\s*[a-zA-Z_][a-zA-Z0-9_]+\s*#', $this->_rendererMock->renderFrom($this->_queryMock));
    }

    public function testRenderTableWithSchemaWithoutAlias()
    {
        $this->_queryMock->from('foo_table', 'testing_schema');

        $this->assertRegExp('#\s*FROM\s*[a-zA-Z_][a-zA-Z0-9_]+\.[a-zA-Z_][a-zA-Z0-9_]+\s*#', $this->_rendererMock->renderFrom($this->_queryMock));
    }

    public function testRenderOneTableWithoutSchemaWithAlias()
    {
        $this->_queryMock->from(array('my_alias' => 'foo_table'));

        $this->assertRegExp('#\s*FROM\s*[a-zA-Z_][a-zA-Z0-9_]+\s+AS\s+[a-zA-Z_][a-zA-Z0-9_]+\s*#', $this->_rendererMock->renderFrom($this->_queryMock));
    }

    public function testRenderTableWithSchemaWithAlias()
    {
        $this->_queryMock->from(array('my_alias' => 'foo_table'), 'testing_schema');

        $this->assertRegExp('#\s*FROM\s*[a-zA-Z_][a-zA-Z0-9_]+\.[a-zA-Z_][a-zA-Z0-9_]+\s+AS\s+[a-zA-Z_][a-zA-Z0-9_]+\s*#', $this->_rendererMock->renderFrom($this->_queryMock));
    }
}
