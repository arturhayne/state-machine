<?php

use StateMachine\API\StateMachineAPI;

require_once(__DIR__ . '/vendor/autoload.php');

$stateMachine = new StateMachineAPI();

try {
    // $stateMachine->createMachine();
    // $result = $stateMachine->processInputDebuging($argv[1]);
    // $result = $stateMachine->processInput($argv[1]);
     $result = $stateMachine->execute($argv[1]);

    echo $result . "\n";
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
