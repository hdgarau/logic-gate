<?php
namespace Tests\Feature;

use LogicGate\LogicGate;
use LogicGate\LogicGatesRoot;
use PHPUnit\Framework\TestCase;

class ReadmeExamplesTest extends TestCase
{
    public function testBlock1()
    {
        //Using '!='
        $gate1 = new LogicGate(4,LogicGate::OP_NOT . LogicGate::OP_EQ);
        self::assertTrue( $gate1->test(1));
        self::assertFalse( $gate1->test(4));

        //Using '>' in a array
        $gate2 = new LogicGate(18,LogicGate::OP_GT);
        self::assertEquals( [0=>21,2=>33],  $gate2->filter([21,3,33])); // [21,33]

        //Using a function
        //the function must have two arguments:
        // - the value to check
        // - value of gate
        $fx = function($val, $ref) { return $val->prop1 == $ref;};
        $gate3 = new LogicGate(5,$fx);
        self::assertTrue( $gate3->test((object)['prop1'=>5])); // [21,33]
    }
    public function testBlock2()
    {
        $gatesRoot = new LogicGatesRoot();
        $gatesRoot->addAND (new LogicGate('^\\d\\d$', LogicGate::OP_REGEX));
        $gatesRoot->addAND (new LogicGate(45, LogicGate::OP_GT));
        $gatesRoot->addOR(new LogicGate(5, LogicGate::OP_GT));
        $gatesRoot->addAND(new LogicGate('^\\d$', LogicGate::OP_REGEX));
        self::assertEquals( [1=>8,3=>88,4=>97], $gatesRoot->filter([11,8,178,88,97]));

        $gatesRoot2 = new LogicGatesRoot();
        $gatesRoot2->addAND (new LogicGate('^8', LogicGate::OP_REGEX));
        $gatesRoot2->addAND ($gatesRoot);
        self::assertEquals( [1=>8,3=>88], $gatesRoot2->filter([11,8,178,88,97]));
    }
    public function testBlock3()
    {
        $fx = function ($el, $ref) { return $el %2 ==0;};
        $arr = [
            ['value' => '^\\d+$', 'next_gate' => 'AND'] ,
            ['operator' => $fx, 'value' => null, 'next_gate' => 'OR'] ,
            ['operator' => LogicGate::OP_GT, 'value' => 60, 'next_gate' => 'anything']
        ];
        $gate = new LogicGatesRoot($arr);
        self::assertTrue($gate->test(61));
        self::assertTrue($gate->test(6));
        self::assertFalse($gate->test(57));
    }
    public function testBlock4()
    {
        //(~:"2$" OR ~:"comodin) AND (~:"1" OR =:"comodin21") AND !=:"comodin12"
        //(finish with two or contains "comodin") and (contains 1 OR is equal "comodin21")
        $lg = new LogicGatesRoot('(' . LogicGate::OP_REGEX . ':"2$" OR ' . LogicGate::OP_REGEX . ':"comodin") '.
            'AND (' . LogicGate::OP_REGEX . ':"1" OR ' . LogicGate::OP_EQ . ':"comodin23") AND '
            . LogicGate::OP_NOT .  LogicGate::OP_EQ . ':"comodin211"');

        self::assertTrue( $lg->test('comodin21')); //true
        self::assertFalse( $lg->test('comodin22')); //false
        self::assertTrue( $lg->test('comodin12')); //false
        self::assertTrue( $lg->test('comodin103')); //true
        self::assertTrue( $lg->test('12')); //true
        self::assertFalse( $lg->test('comodin211')); //false
    }

}
