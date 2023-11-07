<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use StateMachine\API\StateMachineAPI;

class StateMachineAPITest extends TestCase
{
    /**
     * @dataProvider provideMod3
     */
    public function testModThree($input)
    {
        $stateMachine = new StateMachineAPI();
        $stateMachine->createMachine();

        $stateMachine->addState('S1', 1, true);
        $stateMachine->addState('S0', 0, true);
        $stateMachine->addState('S2', 2, true);

        $stateMachine->addAlphabet(['1', '0']);

        $stateMachine->addTransition('S0', '0', 'S0');
        $stateMachine->addTransition('S0', '1', 'S1');

        $stateMachine->addTransition('S1', '0', 'S2');
        $stateMachine->addTransition('S1', '1', 'S0');

        $stateMachine->addTransition('S2', '0', 'S1');
        $stateMachine->addTransition('S2', '1', 'S2');

        $stateMachine->startState('S0');

        $result = $stateMachine->processInput($input);
        $expectedResult = bindec($input) % 3;
        $this->assertEquals($expectedResult, $result);
    }

    public static function provideMod3()
    {
        return [
            '"110"' => ['110'],
            '"1101"' => ['1101'],
            '"1110"' => ['1110'],
            'empty input' => [''],
            '"0"' => ['0'],
            '"1"' => ['1'],
            'big number' => ['111010101010101010101010101011010101010101010011010100101'],
        ];
    }
}
