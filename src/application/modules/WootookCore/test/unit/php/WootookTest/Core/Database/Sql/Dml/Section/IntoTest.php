<?php

namespace WootookTest\Core\Database\Sql\Dml\Section;

include __DIR__ . '/IntoMock.php';

class IntoTest
    extends \PHPUnit_Framework_TestCase
{
    protected $_mock = null;

    public function setUp()
    {
        $adapter = $this->getMockForAbstractClass('Wootook\\Core\\Database\\Adapter\\Adapter');
        $this->_mock = $this->getMockForAbstractClass('WootookTest\\Core\\Database\\Sql\\Dml\\Section\\IntoMock', array($adapter));
    }

    public function testSetTableWithoutSchema()
    {
        $this->_mock->into('foo_table');

        $expected = array(
            'table'  => 'foo_table',
            'schema' => null
            );
        $this->assertEquals($expected, $this->_mock->getPart(IntoMock::INTO));
    }

    public function testSetTableWithSchema()
    {
        $this->_mock->into('foo_table', 'testing_schema');

        $expected = array(
            'table'  => 'foo_table',
            'schema' => 'testing_schema'
            );
        $this->assertEquals($expected, $this->_mock->getPart(IntoMock::INTO));
    }
}
