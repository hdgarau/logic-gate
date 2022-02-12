<?php

namespace LogicGate;
use Exception;


class LogicGate implements iIsEvaluable
{
    use Traits\ErrorHandler;
    use Traits\CommonEvaluableFunction;

    const OP_EQ = '=';
    const OP_GT = '>';
    const OP_LT = '<';
    const OP_REGEX = '~';
    const OP_DEFAULT = self::OP_REGEX;
    const OPERATIONS_ALLOWED = [ self::OP_EQ , self::OP_GT, self::OP_LT , self::OP_REGEX ];

    protected $op_default = null;
    public $value;

    public function __construct( $value, $op_default = self::OP_DEFAULT )
    {
        $this->value = $value;
        $this->setDefault( $op_default );
    }
    public function setDefault( $operator )
    {
        $this->orFail ( $this->_checkOperator($operator), new Exceptions\LogicGatesWrongOperatorException($operator) );
        $this->op_default = $operator;
    }
    public function setValue( $value )
    {
        $this->value = $value;
    }
    public function testWith ( $value, $operator ) : bool
    {
        $this->orFail ( $this->_checkOperator($operator), new Exceptions\LogicGatesWrongOperatorException($operator) );
        if (is_callable($operator))
        {
            return $operator ( $value, $this->value);
        }
        switch ($operator)
        {
            case self::OP_EQ :
                return $this->_testEQ($value);
            case self::OP_GT :
                return $this->_testGT($value);
            case self::OP_LT :
                return $this->_testLT($value);
            case self::OP_REGEX :
                return $this->_testRegex($value);
            default :
                $this->_fail(new Exception("ERROR OP001"));
                return false;
        }
    }
    public function test ( $value ) : bool
    {
        return $this->testWith($value, $this->op_default);
    }
    private function _checkOperator( $operator) :bool
    {
        return  is_callable($operator ) || in_array( $operator, self::OPERATIONS_ALLOWED);
    }
    private function _testEQ(string $value) : bool
    {
        return $value == $this->value;
    }
    private function _testGT(string $value): bool
    {
        return $value > $this->value;
    }
    private function _testLT(string $value): bool
    {
        return $value < $this->value;
    }

    private function _testRegex(string $value)
    {
        return preg_match('/' . $this->value . '/i', $value);
    }
}
