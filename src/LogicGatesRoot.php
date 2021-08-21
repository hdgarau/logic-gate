<?php


namespace LogicGate;

class LogicGatesRoot implements iIsEvaluable
{
    use Traits\ErrorHandler;

    const ARRAY_RESOURCES_REQUIRED_KEYS = [ 'next_gate', 'value'];

    private $_iGroupAnd = 0;
    private $_aGroupAnd = [];

    public function __construct($resource = null)
    {
        if(is_array( $resource) ){
            $this->_setFromArray($resource);
        }
    }

    private function _setFromArray ( array $resource )
    {
        foreach ( $resource as $gate )
        {
            $this->orFail( $this->_checkKeysArrayResource($gate) , new Exceptions\LogicGatesRootWrongArrayKeysException());
            $gate_type = isset($gate_type) && !empty($gate_type) ? $gate_type : 'AND' ;
            $this->orFail( $gate_type == 'AND' || $gate_type == 'OR', new Exceptions\LogicGatesRootWrongGateTypeException($gate_type));
            $this->{'Add' . $gate_type }(new LogicGate( $gate['value'], isset($gate['operator']) ? $gate['operator'] : LogicGate::OP_DEFAULT ));
            $gate_type = trim(strtoupper($gate['next_gate']));
        }
    }
    public function AddAND( iIsEvaluable $gate) : iIsEvaluable
    {
        if ( !isset ($this->_aGroupAnd [ $this->_iGroupAnd] ) )
        {
            $this->_aGroupAnd [ $this->_iGroupAnd ] = new LogicGateGroupAnd();
        }
        $this->_aGroupAnd[ $this->_iGroupAnd ]->addGate( $gate );
        return $gate;
    }
    public function AddOR( iIsEvaluable $gate) : iIsEvaluable
    {
        $this->_iGroupAnd ++;
        return $this->AddAND($gate);
    }
    public function test ( $value ) : bool
    {
        if(empty($this->_aGroupAnd))
        {
            return true;
        }
        foreach ($this->_aGroupAnd as  $group )
        {
            if($group->Test($value))
            {
                return true;
            }
        }
        return false;
    }

    private function _checkKeysArrayResource($gate)
    {
        return count ( array_intersect(array_keys($gate), self::ARRAY_RESOURCES_REQUIRED_KEYS)) === count(self::ARRAY_RESOURCES_REQUIRED_KEYS);
    }
}
