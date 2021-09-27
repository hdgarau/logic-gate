<?php


namespace Tests\Feature;

use LogicGate\LogicGate;
use LogicGate\LogicGatesRoot;
use PHPUnit\Framework\TestCase;

class LogicGateCommonEvaluateFunctionTest extends TestCase
{

    public function testFilterOnEmptyGateRoot ( )
    {
        $gatesRoot = new LogicGatesRoot();
        $array_to_filter = range( 1, 100);

        $this->assertEquals(range(1,100), array_values ( $gatesRoot->filter($array_to_filter ) ) );
    }
    public function testFilterOnGateRoot ( )
    {
        $gatesRoot = new LogicGatesRoot();
        $gatesRoot->AddAND(new LogicGate(17, LogicGate::OP_GT));
        $gatesRoot->AddAND(new LogicGate(51, LogicGate::OP_LT));

        $array_to_filter = range( 1, 100);

        $this->assertEquals(range(18,50), array_values ( $gatesRoot->filter($array_to_filter ) ) );
    }
}