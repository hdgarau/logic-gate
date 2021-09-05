<?php


namespace LogicGate;


interface iIsEvaluable
{
    public function test ( $value ) : bool;
    public function filter ( array $array_to_filter ) : array;
}
