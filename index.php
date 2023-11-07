<?php

use StateMachine\API\StateMachineAPI;

require_once(__DIR__ . '/vendor/autoload.php');

$stateMachine = new StateMachineAPI();

try {
     $stateMachine->createMachine();

     $stateMachine->addState('S1', 1, true);
     $stateMachine->addState('S0', 0, true);
     $stateMachine->addState('S2', 2, true);

     $stateMachine->addAlphabet(['1','0']);     

     $stateMachine->addTransition('S0','0', 'S0');
     $stateMachine->addTransition('S0','1', 'S1');

     $stateMachine->addTransition('S1','0', 'S2');
     $stateMachine->addTransition('S1','1', 'S0');

     $stateMachine->addTransition('S2','0', 'S1');
     $stateMachine->addTransition('S2','1', 'S2');
    
     $stateMachine->startState('S0');

     $result = $stateMachine->processInput($argv[1]);

    echo $result . "\n";
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
