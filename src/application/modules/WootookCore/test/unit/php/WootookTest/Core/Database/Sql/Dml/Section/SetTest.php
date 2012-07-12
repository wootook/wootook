<?php

namespace WootookTest\Core\Database\Sql\Dml\Section;

include __DIR__ . '/SetMock.php';

class SetTest
    extends \PHPUnit_Framework_TestCase
{
    protected $_mock = null;

    public function setUp()
    {
        $adapter = $this->getMockForAbstractClass('Wootook\\Core\\Database\\Adapter\\Adapter');
        $this->_mock = $this->getMockForAbstractClass('WootookTest\\Core\\Database\\Sql\\Dml\\Section\\SetMock', array($adapter));
    }

    public function testSetFieldAndValue()
    {
        $this->_mock->set('foo_field', 18);

        $expected = array(
            array(
                'value' => 18,
                'field' => 'foo_field'
            )
        );

        $this->assertEquals($expected, $this->_mock->getPart(SetMock::SET));
    }

    public function testSetFieldAndValueWithPlaceholder()
    {
        $placeholder = $this->getMockForAbstractClass('Wootook\\Core\\Database\\Sql\\Placeholder\\Placeholder');

        $this->_mock->set('foo_field', $placeholder);

        $expected = array(
            array(
                'value' => $placeholder,
                'field' => 'foo_field'
            )
        );

        $this->assertEquals($expected, $this->_mock->getPart(SetMock::SET));
    }

    public function testSetMultipleFieldAndValueAsArray()
    {
        $this->_mock->set(array(
            'id'        => 18,
            'foo_field' => 'test',
            'example'   => null
            ));

        $expected = array(
            array(
                'value' => 18,
                'field' => 'id'
            ),
            array(
                'value' => 'test',
                'field' => 'foo_field'
            ),
            array(
                'value' => null,
                'field' => 'example'
            )
        );

        $this->assertEquals($expected, $this->_mock->getPart(SetMock::SET));
    }
}
