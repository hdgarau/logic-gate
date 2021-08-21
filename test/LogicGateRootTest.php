<?php

namespace Tests\Feature;

use \LogicGate\LogicGate;
use \LogicGate\LogicGatesRoot;
use \LogicGate\Exceptions\LogicGatesRootWrongArrayKeysException;
use \LogicGate\Exceptions\LogicGatesRootWrongGateTypeException;
use PHPUnit\Framework\TestCase;

class LogicGateRootTest extends TestCase
{
    // Test LogicGroupAND
    public function testEmpty()
    {
        $gatesRoot = new LogicGatesRoot();;
        $this->assertEquals(true, $gatesRoot->test(1));
    }
    public function testRoot()
    {
        $gatesRoot = new LogicGatesRoot();
        $gatesRoot->addAND (new LogicGate('^\\d\\d$', LogicGate::OP_REGEX));
        $gatesRoot->addAND (new LogicGate(45, LogicGate::OP_GT));
        $this->assertEquals(true, $gatesRoot->test(50));
        $this->assertEquals(false, $gatesRoot->test(500));
        $gatesRoot->addOR(new LogicGate(45, LogicGate::OP_LT));
        $gatesRoot->addAND(new LogicGate(1, LogicGate::OP_EQ));
        $this->assertEquals(true, $gatesRoot->test(1));
        $this->assertEquals(false, $gatesRoot->test(10));
    }
    //Great Tree
    public function testGreatTree()
    {
        $isTwoDigitGT10 = new LogicGatesRoot();
        $isTwoDigitGT10->AddAND(new LogicGate(10, LogicGate::OP_GT));
        $isTwoDigitGT10->AddAND(new LogicGate('^\\d\\d$', LogicGate::OP_REGEX));
        $this->assertEquals(true, $isTwoDigitGT10->test(32));
        $this->assertEquals(false, $isTwoDigitGT10->test(3));

        $beginsWithFiveOrIsLTTwenty = new LogicGatesRoot();
        $beginsWithFiveOrIsLTTwenty->addAND(new LogicGate('^5', LogicGate::OP_REGEX));
        $beginsWithFiveOrIsLTTwenty->addOr(new LogicGate(20, LogicGate::OP_LT));

        $isTwoDigitGT10->addAND($beginsWithFiveOrIsLTTwenty);
        $this->assertEquals(false, $isTwoDigitGT10->test(32));
        $this->assertEquals(true, $isTwoDigitGT10->test(12));
        $this->assertEquals(true, $isTwoDigitGT10->test(52));
    }
    public function testSetFromArray()
    {
        $fx = function ($el, $ref) { return $el %2 ==0;};
        $arr = [
            ['value' => '^\\d+$', 'next_gate' => 'AND'] ,
            ['operator' => $fx, 'value' => null, 'next_gate' => 'OR'] ,
            ['operator' => LogicGate::OP_GT, 'value' => 60, 'next_gate' => 'anything']
        ];
        $gate = new LogicGatesRoot($arr);

        $this->assertEquals(false, $gate->test(31));
        $this->assertEquals(true, $gate->test(12));
        $this->assertEquals(true, $gate->test(521));
    }
    //exceptions
    public function testSetFromArrayInvalidGate()
    {
        $this->expectException(LogicGatesRootWrongArrayKeysException::class);
        $fx = function ($el, $ref) { return $el %2 ==0;};
        $arr = [
            [  'next_gate' => 'AND'] ,
            ['operator' => $fx, 'value' => null, 'next_gate' => 'OR'] ,
            ['operator' => LogicGate::OP_GT, 'value' => 60, 'next_gate' => 'anything']
        ];
        $gate = new LogicGatesRoot($arr);
    }
    public function testSetFromArrayInvalidKeys()
    {
        $this->expectException(LogicGatesRootWrongGateTypeException::class);
        $fx = function ($el, $ref) { return $el %2 ==0;};
        $arr = [
            ['operator' => LogicGate::OP_REGEX, 'value' => '^\\d+$', 'next_gate' => 'wrong'] ,
            ['operator' => $fx, 'value' => null, 'next_gate' => 'OR'] ,
            ['operator' => LogicGate::OP_GT, 'value' => 60, 'next_gate' => 'anything']
        ];
        $gate = new LogicGatesRoot($arr);
    }
}
