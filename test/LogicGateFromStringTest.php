<?php


namespace Tests\Feature;

use LogicGate\Exceptions\LogicGatesRootWrongStringResource;
use LogicGate\LogicGate;
use LogicGate\LogicGatesRoot;
use PHPUnit\Framework\TestCase;


class LogicGateFromStringTest extends TestCase
{

    public function _testBadResourceString( )
    {
        $this->expectException(LogicGatesRootWrongStringResource::class);

        new LogicGatesRoot("Bad Resource!");
    }
    public function testSimpleOrResourceString( )
    {
        $lg = new LogicGatesRoot(LogicGate::OP_EQ. ':"asd"');
        $this->assertEquals(false, $lg->test('asd2'));
    }
    public function testSimpleAndResourceString( )
    {
        $lg = new LogicGatesRoot(LogicGate::OP_REGEX. ':"asd" AND ' . LogicGate::OP_REGEX . ':"2$"');
        $this->assertEquals(true, $lg->test('asd2'));
        $lg->AddAND(new LogicGate(5, function($el,$n){ return strlen($el) == $n;}));
        $this->assertEquals(false, $lg->test('asd2'));
        $this->assertEquals(true, $lg->test('asd12'));
    }
    public function testComplexOrResourceString( )
    {
        $lg = new LogicGatesRoot(LogicGate::OP_REGEX. ':"como" AND (' . LogicGate::OP_REGEX . ':"2$" OR ' . LogicGate::OP_EQ . ':"comodin")');
        $this->assertEquals(true, $lg->test('como2'));
        $this->assertEquals(false, $lg->test('como'));
        $this->assertEquals(true, $lg->test('comodin'));
    }
    public function testBrotherComplexResourceString( )
    {

        $lg = new LogicGatesRoot('(' . LogicGate::OP_REGEX . ':"2$" OR ' . LogicGate::OP_REGEX . ':"comodin") '.
            'AND (' . LogicGate::OP_REGEX . ':"1" OR ' . LogicGate::OP_EQ . ':"comodin21")');
        $this->assertEquals(true, $lg->test('comodin21'));
        $this->assertEquals(false, $lg->test('comodin22'));
        $this->assertEquals(true, $lg->test('comodin103'));
        $this->assertEquals(true, $lg->test('12'));
        $this->assertEquals(false, $lg->test('21'));
    }
    public function testChildComplexResourceString( )
    {

        $lg = new LogicGatesRoot('(' . LogicGate::OP_REGEX . ':"2$" OR ' . LogicGate::OP_REGEX . ':"comodin") '.
            'AND (' . LogicGate::OP_REGEX . ':"1" OR ' . LogicGate::OP_EQ . ':"comodin21")');
        $this->assertEquals(true, $lg->test('comodin21'));
        $this->assertEquals(false, $lg->test('comodin22'));
        $this->assertEquals(true, $lg->test('comodin103'));
        $this->assertEquals(true, $lg->test('12'));
        $this->assertEquals(false, $lg->test('21'));
    }
}
