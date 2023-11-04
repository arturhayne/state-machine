<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use StateMachine\Domain\Exceptions\InvalidIntialStateMachine;
use StateMachine\Domain\Exceptions\InvalidStateException;
use StateMachine\Domain\Exceptions\InvalidTransitionAlphabetException;
use StateMachine\Domain\Exceptions\MissingOutputException;
use StateMachine\Domain\StateMachine;
use StateMachine\Domain\Transition;

class StateMachineTest extends TestCase
{
    public function testStartWithoutStates()
    {
        $this->expectException(InvalidIntialStateMachine::class);
        $this->expectExceptionMessage('Invalid intial state machine "S0"');

        $machine = new StateMachine(['0', '1']);
        $machine->start('S0');
    }

    public function testInvalidInitialStateMachine()
    {
        $this->expectException(InvalidIntialStateMachine::class);
        $this->expectExceptionMessage('Invalid intial state machine "S0"');

        // 'states' => ['S0', 'S1', 'S2'], // Q
        // 'initial-state' => 'S0', // q0
        // 'final-states' => ['S0', 'S1', 'S2'], // F
        // 'alphabet' => [1, 0], // Σ
        // 'transitions' => [ // δ
        //     'S0' => ['0' => 'S0', '1' => 'S1'],
        //     'S1' => ['0' => 'S2', '1' => 'S0'],
        //     'S2' => ['0' => 'S1', '1' => 'S2'],
        // ],
        // 'output-map' => ['S0' => 0, 'S1' => 1, 'S2' => 2],

        $machine = new StateMachine(['0', '1']);
        $machine->createStates(['S1', 'S2'], ['S0', 'S1', 'S2'], ['S0' => 0, 'S1' => 1, 'S2' => 2]);
        $machine->start('S0');
    }

    public function testMissingOutput()
    {
        $this->expectException(MissingOutputException::class);
        $this->expectExceptionMessage('There is no output for the state "S0".');

        $machine = new StateMachine(['0', '1']);
        $machine->createStates(['S0', 'S1', 'S2'], ['S0', 'S1', 'S2'], ['S1' => 1, 'S2' => 2]);
    }

    public function testStateWithoutOutput()
    {
        $machine = new StateMachine(['0', '1']);
        $machine->createStates(['S0', 'S1', 'S2'], ['S1', 'S2'], ['S1' => 1, 'S2' => 2]);
        $machine->start('S0');
        $this->assertEquals('S0', $machine->currentState()->name());
        $this->assertNull($machine->currentState()->output());
    }

    public function testAddTransitionsWithoutState()
    {
        $this->expectException(InvalidStateException::class);
        $this->expectExceptionMessage('"S0" is not a valid state.');

        $transitions = [
            'S0' => ['0' => 'S0', '1' => 'S1'],
            'S1' => ['0' => 'S2', '1' => 'S0'],
            'S2' => ['0' => 'S1', '1' => 'S2'],
        ];

        $machine = new StateMachine(['0', '1']);
        $machine->createStates(['S1', 'S2'], ['S1', 'S2'], ['S1' => 1, 'S2' => 2]);
        $machine->addTransitionsToStates($transitions);
    }

    public function testAddTransitionsToStates()
    {
        $transitions = [
            'S0' => ['0' => 'S0', '1' => 'S1'],
            'S1' => ['0' => 'S2', '1' => 'S0'],
            'S2' => ['0' => 'S1', '1' => 'S2'],
        ];

        $machine = new StateMachine(['0', '1']);
        $machine->createStates(['S0', 'S1', 'S2'], ['S0', 'S1', 'S2'], ['S0' => 0, 'S1' => 1, 'S2' => 2]);
        $machine->addTransitionsToStates($transitions);
        $machine->start('S0');

        $currentState = $machine->currentState();
        $stateSransitions = $currentState->transitions();
        $expectedTransitions = [Transition::create('0', 'S0', 'S0'), Transition::create('1', 'S1', 'S0')];
        $this->assertEquals('S0', $currentState->name());
        $this->assertEquals($expectedTransitions, $stateSransitions);
    }

    public function testInvalidProcessInput()
    {
        $this->expectException(InvalidTransitionAlphabetException::class);
        $this->expectExceptionMessage('"a" is not part of the alphabet');

        $transitions = [
            'S0' => ['0' => 'S0', '1' => 'S1'],
            'S1' => ['0' => 'S2', '1' => 'S0'],
            'S2' => ['0' => 'S1', '1' => 'S2'],
        ];

        $machine = new StateMachine(['0', '1']);
        $machine->createStates(['S0', 'S1', 'S2'], ['S0', 'S1', 'S2'], ['S0' => 0, 'S1' => 1, 'S2' => 2]);
        $machine->addTransitionsToStates($transitions);
        $machine->start('S0');
        $machine->processInput('a');
    }

    public function testProcessInput()
    {
        $transitions = [
            'S0' => ['0' => 'S0', '1' => 'S1'],
            'S1' => ['0' => 'S2', '1' => 'S0'],
            'S2' => ['0' => 'S1', '1' => 'S2'],
        ];

        $machine = new StateMachine(['0', '1']);
        $machine->createStates(['S0', 'S1', 'S2'], ['S0', 'S1', 'S2'], ['S0' => 0, 'S1' => 1, 'S2' => 2]);
        $machine->addTransitionsToStates($transitions);
        $machine->start('S0');
        $machine->processInput('1');
        $currentState = $machine->currentState();
        $this->assertEquals('S1', $currentState->name());
    }
}
