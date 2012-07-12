<?php

namespace WootookTest\Core\Database\Sql\Dml\Section;

include __DIR__ . '/ColumnMock.php';

class ColumnTest
    extends \PHPUnit_Framework_TestCase
{
    protected $_mock = null;

    public function setUp()
    {
        $adapter = $this->getMockForAbstractClass('Wootook\\Core\\Database\\Adapter\\Adapter');
        $this->_mock = $this->getMockForAbstractClass('WootookTest\\Core\\Database\\Sql\\Dml\\Section\\ColumnMock', array($adapter));
    }

    public function testAddColumnWithoutTableWithoutAlias()
    {
        $this->_mock->column('foo_column');

        $expected = array(
            array(
                'field' => 'foo_column',
                'alias' => null,
                'table' => null
                )
            );
        $this->assertEquals($expected, $this->_mock->getPart(ColumnMock::COLUMNS));
    }

    public function testAddColumnWithTableWithoutAlias()
    {
        $this->_mock->column('foo_column', 'testing_table');

        $expected = array(
            array(
                'field'  => 'foo_column',
                'alias'  => null,
                'table' => 'testing_table'
            )
        );

        $this->assertEquals($expected, $this->_mock->getPart(ColumnMock::COLUMNS));
    }

    public function testAddOneTableWithoutTableWithAlias()
    {
        $this->_mock->column(array('my_alias' => 'foo_column'));

        $expected = array(
            array(
                'field' => 'foo_column',
                'alias' => 'my_alias',
                'table' => null
            )
        );
        $this->assertEquals($expected, $this->_mock->getPart(ColumnMock::COLUMNS));
    }

    public function testAddColumnWithTableWithAlias()
    {
        $this->_mock->column(array('my_alias' => 'foo_column'), 'testing_table');

        $expected = array(
            array(
                'field' => 'foo_column',
                'alias' => 'my_alias',
                'table' => 'testing_table'
            )
        );

        $this->assertEquals($expected, $this->_mock->getPart(ColumnMock::COLUMNS));
    }
}
