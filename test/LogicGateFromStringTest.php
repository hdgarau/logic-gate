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
    public function testSimpleResourceString( )
    {
        $lg = new LogicGatesRoot(LogicGate::OP_EQ. ":asd");
        $this->assertEquals(false, $lg->test('asd2'));
    }
}