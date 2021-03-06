<?php


namespace LogicGate\Exceptions;
use Exception;
use LogicGate\LogicGatesRoot;
use Throwable;

class LogicGatesCharScapeNotAllowedException extends Exception
{
    public function __construct(  $code = 0, Throwable $previous = null)
    {
        $msg = "Scape char not allowed: (Allowed: " . implode(' ' ,LogicGatesRoot::ARRAY_CHARACTERS_SCAPE_ALLOWED) . ")";
        parent::__construct($msg, $code, $previous);
    }
}
