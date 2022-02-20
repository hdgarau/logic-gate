<?php


namespace LogicGate;

use Common\Classes\Parse\ParsedEntity;
use Common\Functions\StringParse;
use Exception;
use LogicGate\Exceptions\LogicGatesCharScapeNotAllowedException;
use LogicGate\Exceptions\LogicGatesRootWrongStringResource;

class LogicGatesRoot implements iIsEvaluable
{
    use Traits\ErrorHandler;
    use Traits\CommonEvaluableFunction;

    const ARRAY_RESOURCES_KEYS_REQUIRED = [ 'next_gate', 'value'];
    const ARRAY_CHARACTERS_SCAPE_ALLOWED = ['"',"'","@","#"];
    private $_iGroupAnd = 0;
    private $_aGroupAnd = [];
    private $_char_scape = '"';

    public function __construct ( $resource = null)
    {
        if ( is_array ( $resource ) ){
            $this->_setFromArray ( $resource );
        }
        if ( is_string ( $resource ) )
        {
            $this->_setFromString ( $resource );
        }
    }

    private function _setFromArray ( array $resource )
    {
        foreach ( $resource as $gate )
        {
            $this->orFail( $this->_checkKeysArrayResource($gate) , new Exceptions\LogicGatesRootWrongArrayKeysException());
            $gate_type = isset($gate_type) && !empty($gate_type) ? $gate_type : 'AND' ;
            $this->orFail( $gate_type == 'AND' || $gate_type == 'OR', new Exceptions\LogicGatesRootWrongGateTypeException($gate_type));
            $this->{'Add' . $gate_type }(new LogicGate( $gate['value'], $gate['operator'] ?? LogicGate::OP_DEFAULT));
            $gate_type = trim(strtoupper($gate['next_gate']));
        }
    }
    private function _setFromString ( string $resource )
    {
        $oParsedGroup = StringParse::strToParsedGroupParenthesis($resource);
        $this->_addFromEntity($oParsedGroup);
    }
    private function _addFromEntity ( $oParsedGroup , $previousGate = 'AND')
    {

        foreach ( $oParsedGroup->getEntities() as $entity )
        {
            if($entity instanceof ParsedEntity)
            {
                $this->_parseStrResource( ( string ) $entity );
            }
            else
            {
                $obj = new LogicGatesRoot();
                $obj->_addFromEntity ( $entity );
                $this->{'add' . $previousGate}($obj);
            }
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

    private function _checkKeysArrayResource($gate): bool
    {
        return count ( array_intersect(array_keys($gate), self::ARRAY_RESOURCES_KEYS_REQUIRED)) === count(self::ARRAY_RESOURCES_KEYS_REQUIRED);
    }

    private function _parseStrResource(string $resource)
    {
        $previousGate = 'AND';
        if(trim($resource) == 'AND' || trim($resource) == 'OR')
        {
            return;
        }
        while(!empty(trim($resource)))
        {
            preg_match('/^.+?:' . $this->_char_scape . '[^' . $this->_char_scape . ']+?' . $this->_char_scape . '/i', $resource, $matches);
            try {
                list($operator, $value) = explode(':', trim($matches[0]));
                if(strtoupper(substr($operator,0,3)) == 'OR ')
                {
                    $previousGate = 'OR';
                    $operator = trim(substr($operator,3));
                }
                if(strtoupper(substr($operator,0,4) == 'AND '))
                {
                    $previousGate = 'AND';
                    $operator = trim(substr($operator,4));
                }
            }
            catch (Exception $e){
                print_r($resource);
                die();
            }
            $value = trim($value,$this->_char_scape);
            $this->{'add' . $previousGate}(new LogicGate($value, $operator));
            $resource = substr($resource, strlen($matches[0]));
            if(preg_match('/^\s+(AND|OR)\s+/i', $resource, $matches))
            {
                $resource = substr($resource, strlen($matches[0]));
                $previousGate = trim($matches[1]);
            }
            else
            {
                if(!empty(trim($resource)))
                {
                    throw new LogicGatesRootWrongStringResource('Expect end string. Having (' . $resource .')');
                }
            }
        }
    }

    /**
     * @param string $_char_scape
     */
    public function setCharScape(string $_char_scape)
    {
        if(!in_array($_char_scape , self::ARRAY_CHARACTERS_SCAPE_ALLOWED))
        {
            throw new LogicGatesCharScapeNotAllowedException();
        }
        $this->_char_scape = $_char_scape;
    }
}
