<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use StateMachine\API\Exceptions\InputNotStringException;
use StateMachine\API\Exceptions\NotAcceptedFinalStateException;
use StateMachine\API\Exceptions\NotInitializedMachineException;
use StateMachine\API\StateMachineAPI;
use StateMachine\Domain\Exceptions\InvalidIntialStateMachine;
use StateMachine\Domain\Exceptions\InvalidStateInputException;
use StateMachine\Infra\ConfigurationManager;

class StateMachineAPITest extends TestCase
{
    /**
     * @dataProvider provideMod3
     */
    public function testModThree($input)
    {
        $handler = new StateMachineAPI();

        ConfigurationManager::set('states', ['S0', 'S1', 'S2']);
        ConfigurationManager::set('alphabet', ['0', '1']);
        ConfigurationManager::set('output-map', ['S0' => 0, 'S1' => 1, 'S2' => 2]);
        ConfigurationManager::set('initial-state', 'S0');
        ConfigurationManager::set('final-states', ['S0', 'S1', 'S2']);
        ConfigurationManager::set('transitions', [
            'S0' => ['0' => 'S0', '1' => 'S1'],
            'S1' => ['0' => 'S2', '1' => 'S0'],
            'S2' => ['0' => 'S1', '1' => 'S2'],
        ]);

        $result = $handler->execute($input);
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

    public function testProcessingInput()
    {
        $handler = new StateMachineAPI();

        ConfigurationManager::set('states', ['S0', 'S1', 'S2']);
        ConfigurationManager::set('alphabet', ['0', '1']);
        ConfigurationManager::set('output-map', ['S0' => 0, 'S1' => 1, 'S2' => 2]);
        ConfigurationManager::set('initial-state', 'S0');
        ConfigurationManager::set('final-states', ['S0', 'S1', 'S2']);
        ConfigurationManager::set('transitions', [
            'S0' => ['0' => 'S0', '1' => 'S1'],
            'S1' => ['0' => 'S2', '1' => 'S0'],
            'S2' => ['0' => 'S1', '1' => 'S2'],
        ]);

        $input = '111111';
        $handler->createMachine();
        $result = $handler->processInput($input);
        $expectedResult = bindec($input) % 3;
        $this->assertEquals($expectedResult, $result);
    }

    public function testOutputWithNoInput()
    {
        $handler = new StateMachineAPI();

        ConfigurationManager::set('states', ['S0', 'S1', 'S2']);
        ConfigurationManager::set('alphabet', ['0', '1']);
        ConfigurationManager::set('output-map', ['S0' => 0, 'S1' => 1, 'S2' => 2]);
        ConfigurationManager::set('initial-state', 'S0');
        ConfigurationManager::set('final-states', ['S0', 'S1', 'S2']);
        ConfigurationManager::set('transitions', [
            'S0' => ['0' => 'S0', '1' => 'S1'],
            'S1' => ['0' => 'S2', '1' => 'S0'],
            'S2' => ['0' => 'S1', '1' => 'S2'],
        ]);

        $handler->createMachine();
        // Initial state
        $this->assertEquals(0, $handler->output());
    }

    public function testDeadEndState()
    {
        $this->expectException(InvalidStateInputException::class);
        $this->expectExceptionMessage('Invalid input "1" for state "S2');

        $handler = new StateMachineAPI();

        ConfigurationManager::set('states', ['S0', 'S1', 'S2']);
        ConfigurationManager::set('alphabet', ['0', '1']);
        ConfigurationManager::set('output-map', ['S0' => 0, 'S1' => 1, 'S2' => 2]);
        ConfigurationManager::set('initial-state', 'S0');
        ConfigurationManager::set('final-states', ['S0', 'S1', 'S2']);
        ConfigurationManager::set('transitions', [
            'S0' => ['0' => 'S0', '1' => 'S1'],
            'S1' => ['0' => 'S2', '1' => 'S0'],
            'S2' => ['0' => 'S1'],
        ]);

        $handler->execute('1011');
    }

    public function testNotAccepetedFinalState()
    {
        $this->expectException(NotAcceptedFinalStateException::class);
        $this->expectExceptionMessage('State "S2" is not accepted as a final state.');

        $handler = new StateMachineAPI();

        ConfigurationManager::set('states', ['S0', 'S1', 'S2']);
        ConfigurationManager::set('alphabet', ['0', '1']);
        ConfigurationManager::set('output-map', ['S0' => 0, 'S1' => 1, 'S2' => 2]);
        ConfigurationManager::set('initial-state', 'S0');
        ConfigurationManager::set('final-states', ['S0', 'S1']);
        ConfigurationManager::set('transitions', [
            'S0' => ['0' => 'S0', '1' => 'S1'],
            'S1' => ['0' => 'S2', '1' => 'S0'],
            'S2' => ['0' => 'S1', '1' => 'S2'],
        ]);

        $handler->execute('10');
    }

    public function testInputIsNotAString()
    {
        $this->expectException(InputNotStringException::class);
        $this->expectExceptionMessage('The input must be string');

        $handler = new StateMachineAPI();

        ConfigurationManager::set('states', ['S0', 'S1', 'S2']);
        ConfigurationManager::set('alphabet', ['0', '1']);
        ConfigurationManager::set('output-map', ['S0' => 0, 'S1' => 1, 'S2' => 2]);
        ConfigurationManager::set('initial-state', 'S0');
        ConfigurationManager::set('final-states', ['S0', 'S1', 'S2']);
        ConfigurationManager::set('transitions', [
            'S0' => ['0' => 'S0', '1' => 'S1'],
            'S1' => ['0' => 'S2', '1' => 'S0'],
            'S2' => ['0' => 'S1', '1' => 'S2'],
        ]);

        $handler->execute(true);
    }

    public function testProcessInputWithNoMachine()
    {
        $this->expectException(NotInitializedMachineException::class);
        $this->expectExceptionMessage('State Machine not initialized.');

        $handler = new StateMachineAPI();

        ConfigurationManager::set('states', ['S0', 'S1', 'S2']);
        ConfigurationManager::set('alphabet', ['0', '1']);
        ConfigurationManager::set('output-map', ['S0' => 0, 'S1' => 1, 'S2' => 2]);
        ConfigurationManager::set('initial-state', 'S0');
        ConfigurationManager::set('final-states', ['S0', 'S1', 'S2']);
        ConfigurationManager::set('transitions', [
            'S0' => ['0' => 'S0', '1' => 'S1'],
            'S1' => ['0' => 'S2', '1' => 'S0'],
            'S2' => ['0' => 'S1', '1' => 'S2'],
        ]);

        $handler->processInput('111');
    }

    public function testOutputWithNoMachine()
    {
        $this->expectException(NotInitializedMachineException::class);
        $this->expectExceptionMessage('State Machine not initialized.');

        $handler = new StateMachineAPI();

        ConfigurationManager::set('states', ['S0', 'S1', 'S2']);
        ConfigurationManager::set('alphabet', ['0', '1']);
        ConfigurationManager::set('output-map', ['S0' => 0, 'S1' => 1, 'S2' => 2]);
        ConfigurationManager::set('initial-state', 'S0');
        ConfigurationManager::set('final-states', ['S0', 'S1', 'S2']);
        ConfigurationManager::set('transitions', [
            'S0' => ['0' => 'S0', '1' => 'S1'],
            'S1' => ['0' => 'S2', '1' => 'S0'],
            'S2' => ['0' => 'S1', '1' => 'S2'],
        ]);

        $handler->output();
    }

    public function testInvalidInitialStateMachine()
    {
        $this->expectException(InvalidIntialStateMachine::class);
        $this->expectExceptionMessage('Invalid intial state machine "S0"');

        $handler = new StateMachineAPI();

        ConfigurationManager::set('states', ['S1', 'S2']);
        ConfigurationManager::set('alphabet', ['0', '1']);
        ConfigurationManager::set('output-map', ['S0' => 0, 'S1' => 1, 'S2' => 2]);
        ConfigurationManager::set('initial-state', 'S0');
        ConfigurationManager::set('final-states', ['S0', 'S1', 'S2']);
        ConfigurationManager::set('transitions', [
            'S0' => ['0' => 'S0', '1' => 'S1'],
            'S1' => ['0' => 'S2', '1' => 'S0'],
            'S2' => ['0' => 'S1', '1' => 'S2'],
        ]);

        $input = '111111';
        $handler->createMachine();
    }
}
