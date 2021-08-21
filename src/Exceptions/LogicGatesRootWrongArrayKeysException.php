<?php


namespace LogicGate\Exceptions;
use \LogicGate\LogicGate;

class LogicGatesRootWrongArrayKeysException extends \Exception
{
    public function __construct(  $code = 0, \Throwable $previous = null)
    {
        $msg = "To generate a LogicGatesRoot by Array must by [arr1, arr2 ...]. Each array must has next keys: (next_gate, value, operator (optional: default " . LogicGate::OP_DEFAULT . ") )";
        parent::__construct($msg, $code, $previous);
    }
}
