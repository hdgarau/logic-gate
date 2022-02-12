<?php


namespace LogicGate\Exceptions;
use \LogicGate\LogicGate;

class LogicGatesWrongOperatorException extends \Exception
{
    public function __construct( $operator , $code = 0, \Throwable $previous = null)
    {
        $msg = "Operator Wrong (" . (is_scalar($operator) ? $operator : 'isn`t scalar') . "). Allow Operators (" . implode(',' , LogicGate::OPERATIONS_ALLOWED) . ")";
        parent::__construct($msg, $code, $previous);
    }
}
