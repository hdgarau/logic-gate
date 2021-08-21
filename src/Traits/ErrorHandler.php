<?php


namespace LogicGate\Traits;


trait ErrorHandler
{
    public function orFail (bool $condition,  \Exception $e)
    {
        if ( ! $condition )
        {
            $this->_fail( $e );
        }
    }
    private function _fail(\Exception $e)
    {
        throw $e;
    }
}
