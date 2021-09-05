<?php


namespace LogicGate\Traits;


trait CommonEvaluableFunction
{
    public function filter ( array $array_to_filter) : array
    {
        return array_filter(  $array_to_filter, function ( $el ) { return call_user_func( [$this, 'test'], $el); } );
    }
}