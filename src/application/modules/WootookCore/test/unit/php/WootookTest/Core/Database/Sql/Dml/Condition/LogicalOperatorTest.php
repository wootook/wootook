<?php

namespace WootookTest\Core\Database\Sql\Dml\Condition;

use Wootook\Core\Database\Sql\Dml;

class LogicalOperatorTest
    extends ConditionTest
{
    public function setUp()
    {
        $this->_setUp();

        $this->_conditionMockClassName = 'Wootook\\Core\\Database\\Sql\\Dml\\Condition\\LogicalOperator';
    }

    protected function buildMock(Array $methods = array(), $callOriginalconstructor = true, $callOriginalClone = true)
    {
        return $this->getMockForAbstractClass($this->_conditionMockClassName, array($this->_queryMock), '', $callOriginalconstructor, $callOriginalClone, true, $methods);
    }

    public function testOperatorAccessMethods()
    {
        $conditionMock = $this->buildMock(array($this->_queryMock));

        $conditionMock->setOperator(Dml\Section\WhereAware::OPERATOR_NOT);
        $this->assertEquals(Dml\Section\WhereAware::OPERATOR_NOT, $conditionMock->getOperator());

        $conditionMock->setOperator(Dml\Section\WhereAware::OPERATOR_AND);
        $this->assertEquals(Dml\Section\WhereAware::OPERATOR_AND, $conditionMock->getOperator());

        $conditionMock->setOperator(Dml\Section\WhereAware::OPERATOR_NAND);
        $this->assertEquals(Dml\Section\WhereAware::OPERATOR_NAND, $conditionMock->getOperator());

        $conditionMock->setOperator(Dml\Section\WhereAware::OPERATOR_OR);
        $this->assertEquals(Dml\Section\WhereAware::OPERATOR_OR, $conditionMock->getOperator());

        $conditionMock->setOperator(Dml\Section\WhereAware::OPERATOR_NOR);
        $this->assertEquals(Dml\Section\WhereAware::OPERATOR_NOR, $conditionMock->getOperator());

        $conditionMock->setOperator(Dml\Section\WhereAware::OPERATOR_XOR);
        $this->assertEquals(Dml\Section\WhereAware::OPERATOR_XOR, $conditionMock->getOperator());

        $conditionMock->setOperator(Dml\Section\WhereAware::OPERATOR_NXOR);
        $this->assertEquals(Dml\Section\WhereAware::OPERATOR_NXOR, $conditionMock->getOperator());
    }

    public function testNewOperatorAssignment()
    {
        $conditionMock = $this->buildMock(array($this->_queryMock));
        $closure = function(\Wootook\Core\Database\Sql\Dml\Condition\LogicalOperator $conditions){
            return 'foo';
            };
        $conditionMock->addAllowedOperator('inexistent', $closure);

        $this->assertSame($closure, $conditionMock->getAllowedOperatorRenderer('inexistent'));
    }

    /**
     * @expectedException \Wootook\Core\Exception\InvalidArgumentException
     */
    public function testInexistentOperatorAssignment()
    {
        $conditionMock = $this->buildMock(array($this->_queryMock));
        $conditionMock->setOperator('inexistent');
    }
}
