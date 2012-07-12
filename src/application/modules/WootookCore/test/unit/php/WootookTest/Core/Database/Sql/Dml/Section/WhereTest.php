<?php

namespace WootookTest\Core\Database\Sql\Dml\Section;

include __DIR__ . '/WhereMock.php';

class WhereTest
    extends \PHPUnit_Framework_TestCase
{
    protected $_mock = null;

    public function setUp()
    {
        $adapter = $this->getMockForAbstractClass('Wootook\\Core\\Database\\Adapter\\Adapter');
        $this->_mock = $this->getMockForAbstractClass('WootookTest\\Core\\Database\\Sql\\Dml\\Section\\WhereMock', array($adapter));
    }

    public function testAddScalarCondition()
    {
        $this->markTestIncomplete();
        $this->_mock->where('testing_field', 23);

        $expected = array(
            array(
                'field' => 'foo_column',
                'alias' => null,
                'table' => null
                )
            );
        $this->assertEquals($expected, $this->_mock->getPart(WhereMock::WHERE));
    }

    public function testAddColumnWithTableWithoutAlias()
    {
        $this->markTestIncomplete();
        $this->_mock->where('testing_field', 23);

        $expected = array(
            array(
                'field'  => 'foo_column',
                'alias'  => null,
                'table' => 'testing_table'
            )
        );

        $this->assertEquals($expected, $this->_mock->getPart(WhereMock::WHERE));
    }

    public function testAddOneTableWithoutTableWithAlias()
    {
        $this->markTestIncomplete();
        $this->_mock->where('testing_field', 23);

        $expected = array(
            array(
                'field' => 'foo_column',
                'alias' => 'my_alias',
                'table' => null
            )
        );
        $this->assertEquals($expected, $this->_mock->getPart(WhereMock::WHERE));
    }

    public function testAddColumnWithTableWithAlias()
    {
        $this->markTestIncomplete();
        $this->_mock->where('testing_field', 23);

        $expected = array(
            array(
                'field' => 'foo_column',
                'alias' => 'my_alias',
                'table' => 'testing_table'
            )
        );

        $this->assertEquals($expected, $this->_mock->getPart(WhereMock::WHERE));
    }
}
