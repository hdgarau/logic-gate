<?php


namespace LogicGate;

class LogicGateGroupAnd implements iIsEvaluable
{
    use Traits\ErrorHandler;
    use Traits\CommonEvaluableFunction;

    protected $gates = [];
    public function addGate( iIsEvaluable $gate) : iIsEvaluable
    {
        array_push($this->gates, $gate);
        return $gate;
    }
    public function test ( $value ) : bool
    {
        foreach ( $this->gates as $gate )
        {
            if(!$gate->test ( $value))
            {
                return false;
            }
        }
        return true;
    }
}
