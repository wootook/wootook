<?php

namespace WootookUnit\Core\Database\Sql\Dml\Section\Renderer;

include __DIR__ . '/LimitMock.php';

class LimitTest
    extends \PHPUnit_Framework_TestCase
{
    protected $_queryMock = null;
    protected $_rendererMock = null;

    public function setUp()
    {
        $adapter = $this->getMockForAbstractClass('Wootook\\Core\\Database\\Adapter\\Adapter');
        $this->_queryMock = $this->getMockForAbstractClass('WootookUnit\\Core\\Database\\Sql\\Dml\\Section\\LimitMock', array($adapter));
        $this->_rendererMock = $this->getMockForAbstractClass('WootookUnit\\Core\\Database\\Sql\\Dml\\Section\\Renderer\\LimitMock');
    }

    public function testRenderLimitWithoutOffset()
    {
        $this->_queryMock->limit(100);

        $this->assertRegExp('#\s*LIMIT\s+\d+\s*#', $this->_rendererMock->renderLimit($this->_queryMock));
    }

    public function testRenderLimitWithOffset()
    {
        $this->_queryMock->limit(100, 20);

        $this->assertRegExp('#\s*LIMIT\s+\d+\s*,\s*\d+\s*#', $this->_rendererMock->renderLimit($this->_queryMock));
    }

    public function testRenderNoLimit()
    {
        $this->assertEmpty($this->_rendererMock->renderLimit($this->_queryMock));
    }
}
