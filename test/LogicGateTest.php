<?php

namespace Tests\Feature;

use LogicGate\LogicGate;
use LogicGate\Exceptions\LogicGatesWrongOperatorException;
use PHPUnit\Framework\TestCase;

class LogicGateTest extends TestCase
{
    public function testOperatorCallable()
    {
        $gate = new LogicGate(4);
        $fx = function ($n, $ref) { return $n%2==0 && $n > $ref;};

        $this->assertEquals(true, $gate->testWith(6, $fx));
        $this->assertEquals(false, $gate->testWith(5, $fx));
        $this->assertEquals(false, $gate->testWith(4, $fx));
    }
    public function testSetDefaultFX()
    {
        $fx = function ($n, $ref) { return 'test';};
        $gate = new LogicGate('test',$fx);
        //$gate->setDefault($fx);
        $this->assertEquals(true, $gate->test('test'));
    }
    public function testOperatorEqTrue()
    {
        $gate = new LogicGate('test');
        $this->assertEquals(true, $gate->testWith('test', LogicGate::OP_EQ));
    }
    public function testOperatorEqFalse()
    {
        $gate = new LogicGate('test');
        $this->assertEquals(false, $gate->testWith('testno', LogicGate::OP_EQ));
    }
    public function testOperatorGTTrue()
    {
        $gate = new LogicGate(15);
        $this->assertEquals(true, $gate->testWith(17, LogicGate::OP_GT));
    }
    public function testOperatorGTFalse()
    {
        $gate = new LogicGate(17);
        $this->assertEquals(false, $gate->testWith(17, LogicGate::OP_GT));
    }
    public function testOperatorLTTrue()
    {
        $gate = new LogicGate(18);
        $this->assertEquals(true, $gate->testWith(17, LogicGate::OP_LT));
    }
    public function testOperatorLTFalse()
    {
        $gate = new LogicGate(17);
        $this->assertEquals(false, $gate->testWith(17, LogicGate::OP_LT));
    }
    public function testOperatorRegExTrue()
    {
        $gate = new LogicGate('^\d\d$');
        $this->assertEquals(true, $gate->testWith('55', LogicGate::OP_REGEX));
    }
    public function testOperatorRegExFalse()
    {
        $gate = new LogicGate('testno');
        $this->assertEquals(false, $gate->testWith('test', LogicGate::OP_REGEX));
    }
    //exceptions
    public function testWrongOperator()
    {
        $gate = new LogicGate('test');
        $this->expectException(LogicGatesWrongOperatorException::class);
        $gate->testWith('test', ['a','b']);
    }
}
