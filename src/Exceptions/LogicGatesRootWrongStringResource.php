<?php


namespace LogicGate\Exceptions;


class LogicGatesRootWrongStringResource extends \Exception
{
    public function __construct( $resource , $code = 0, \Throwable $previous = null)
{
    $msg = "String resource Wrong (" . $resource . "). See documentation to see how to make a string resource";
    parent::__construct($msg, $code, $previous);
}
}