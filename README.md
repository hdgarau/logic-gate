# logic-gate

## Description:

With LogicGate you can define an evaluable-logic-tree and filter values or array.
This could be useful when you need to do one or more complex filters.
That tool allows to define a filter quietly with a string too.
A LogicRoot has one o more Logic Gate. You can evaluate a single LogicGate

## Use:

### LogicGate Operators

There is ford operators allowed (=,>,<,~) or a callable object.

#### Examples

<pre><code>//Using '='
$gate1 = new LogicGate(4,LogicGate::OP_EQ);
$gate1->test(1); //false

//Using '>' in a array
$gate2 = new LogicGate(18,OP_GT);
$arr_filtered = $gate2->filter([21,3,33]); // [21,33]

//Using a function
//the function must have two arguments:
// - the value to check
// - value of gate
$fx = function($val, $ref) { return $val->prop1 == $ref;};
$gate3 = new LogicGate(5,$fx);
$gate3->test((object)['prop1'=>5]); //true
</code></pre>

### How to use

- You can to define a RootGate (tree) in three different ways:
  - Using methods 'addAND' and 'addOR'
  - Using an array.
  - Using a string.

### Using 'add' methods

That is the best way for a healthy order and to do some branches with some different conditions.<br>
You can add a LogicGate or a LogicGateRoot (or any class that implement iIsEvaluable)

#### Example

<pre><code> //two-digit number greater than 45 or one-digit number greater than five       
        $gatesRoot = new LogicGatesRoot();
        $gatesRoot->addAND (new LogicGate('^\\d\\d$', LogicGate::OP_REGEX));
        $gatesRoot->addAND (new LogicGate(45, LogicGate::OP_GT));
        $gatesRoot->addOR(new LogicGate(5, LogicGate::OP_GT));
        $gatesRoot->addAND(new LogicGate('^\\d$', LogicGate::OP_REGEX));
        $gatesRoot->filter([11,8,178,88,97]); //[8,88,97]
        
        $gatesRoot2 = new LogicGatesRoot();
        $gatesRoot2->addAND (new LogicGate('^8', LogicGate::OP_REGEX));
        $gatesRoot2->addAND ($gatesRoot);
        $gatesRoot->filter([11,8,178,88,97]); //[8,88]

</code></pre>

### Using an array

If you have an array with conditions you can make the tree using it.<br>
The keys are:
- operator (optional) default regex
- value (required): value of reference
- next_gate (required) (OR/AND)

#### Example

<pre><code> $fx = function ($el, $ref) { return $el %2 ==0;};
        $arr = [
            ['value' => '^\\d+$', 'next_gate' => 'AND'] ,
            ['operator' => $fx, 'value' => null, 'next_gate' => 'OR'] ,
            ['operator' => LogicGate::OP_GT, 'value' => 60, 'next_gate' => 'anything']
        ];
        $gate = new LogicGatesRoot($arr);
        $gate->test(61);//true
        $gate->test(6);//true
        $gate->test(57);//false
</code></pre>

### Using an string

the minimalist mode to make a complex tree is with a strong query.<br>

The rules are:
- {:operator}:"{:value}" {AND/OR}
- support parenthesis query 
- operator can be <>=~ or a name function. 
- value only can be a string

#### Example

<pre><code> 
        //(~:"2$" OR ~:"comodin) AND (~:"1" OR =:"comodin21")
        //(finish with two or contains "comodin") and (contains 1 OR is equal "comodin21")
        $lg = new LogicGatesRoot('(' . LogicGate::OP_REGEX . ':"2$" OR ' . LogicGate::OP_REGEX . ':"comodin") '.
            'AND (' . LogicGate::OP_REGEX . ':"1" OR ' . LogicGate::OP_EQ . ':"comodin21")');
        $lg->test('comodin21'); //true
        $lg->test('comodin22'); //false
        $lg->test('comodin103'); //true
        $lg->test('12'); //true
        $lg->test('21'); //false
</code></pre>

## Examples
- See TestCases for more examples.
