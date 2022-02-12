<?php


namespace LogicGate\Exceptions;

use Exception;
use Throwable;

class LogicGatesRootWrongGateTypeException extends Exception
{
    public function __construct( $type , $code = 0, Throwable $previous = null)
    {
        $msg = "Type Wrong (" . (is_scalar($type) ? $type : 'isn`t scalar') . "). Allow Types ( AND, OR)";
        parent::__construct($msg, $code, $previous);
    }
}
