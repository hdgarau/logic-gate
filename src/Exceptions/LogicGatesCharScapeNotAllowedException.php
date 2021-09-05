<?php


namespace LogicGate\Exceptions;
use LogicGate\LogicGatesRoot;

class LogicGatesCharScapeNotAllowedException extends \Exception
{
    public function __construct(  $code = 0, \Throwable $previous = null)
    {
        $msg = "Scape char not allowed: (Allowed: " . implode(' ' ,LogicGatesRoot::ARRAY_ALLOWED_CHARACTERS_SCAPE) . ")";
        parent::__construct($msg, $code, $previous);
    }
}
