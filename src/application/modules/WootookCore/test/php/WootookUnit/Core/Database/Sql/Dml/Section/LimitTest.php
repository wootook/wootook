<?php

namespace WootookUnit\Core\Database\Sql\Dml\Section;

include __DIR__ . '/LimitMock.php';

class LimitTest
    extends \PHPUnit_Framework_TestCase
{
    protected $_mock = null;

    public function setUp()
    {
        $adapter = $this->getMockForAbstractClass('Wootook\\Core\\Database\\Adapter\\Adapter');
        $this->_mock = $this->getMockForAbstractClass('WootookUnit\\Core\\Database\\Sql\\Dml\\Section\\LimitMock', array($adapter));
    }

    public function testSetLimitWithoutOffset()
    {
        $this->_mock->limit(100);

        $this->assertEquals(100, $this->_mock->getPart(LimitMock::LIMIT));
        $this->assertEquals(0, $this->_mock->getPart(LimitMock::OFFSET));
    }

    public function testSetLimitWithOffset()
    {
        $this->_mock->limit(100, 20);

        $this->assertEquals(100, $this->_mock->getPart(LimitMock::LIMIT));
        $this->assertEquals(20, $this->_mock->getPart(LimitMock::OFFSET));
    }
}
