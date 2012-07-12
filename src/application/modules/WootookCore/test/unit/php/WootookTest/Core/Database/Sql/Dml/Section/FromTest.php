<?php

namespace WootookTest\Core\Database\Sql\Dml\Section;

include __DIR__ . '/FromMock.php';

class FromTest
    extends \PHPUnit_Framework_TestCase
{
    protected $_mock = null;

    public function setUp()
    {
        $adapter = $this->getMockForAbstractClass('Wootook\\Core\\Database\\Adapter\\Adapter');
        $this->_mock = $this->getMockForAbstractClass('WootookTest\\Core\\Database\\Sql\\Dml\\Section\\FromMock', array($adapter));
    }

    public function testSetOneTableWithoutSchemaWithoutAlias()
    {
        $this->_mock->from('foo_table');

        $expected = array(
            array(
                'table'  => 'foo_table',
                'alias'  => null,
                'schema' => null
                )
            );
        $this->assertEquals($expected, $this->_mock->getPart(FromMock::FROM));
    }

    public function testSetTableWithSchemaWithoutAlias()
    {
        $this->_mock->from('foo_table', 'testing_schema');

        $expected = array(
            array(
                'table'  => 'foo_table',
                'alias'  => null,
                'schema' => 'testing_schema'
            )
        );

        $this->assertEquals($expected, $this->_mock->getPart(FromMock::FROM));
    }

    public function testSetOneTableWithoutSchemaWithAlias()
    {
        $this->_mock->from(array('my_alias' => 'foo_table'));

        $expected = array(
            array(
                'table'  => 'foo_table',
                'alias'  => 'my_alias',
                'schema' => null
            )
        );
        $this->assertEquals($expected, $this->_mock->getPart(FromMock::FROM));
    }

    public function testSetTableWithSchemaWithAlias()
    {
        $this->_mock->from(array('my_alias' => 'foo_table'), 'testing_schema');

        $expected = array(
            array(
                'table'  => 'foo_table',
                'alias'  => 'my_alias',
                'schema' => 'testing_schema'
            )
        );

        $this->assertEquals($expected, $this->_mock->getPart(FromMock::FROM));
    }
}
