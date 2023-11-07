<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use StateMachine\Domain\Exceptions\InputNotStringException;
use StateMachine\Domain\Exceptions\InvalidIntialStateMachine;
use StateMachine\Domain\Exceptions\InvalidStateException;
use StateMachine\Domain\Exceptions\InvalidTransitionAlphabetException;
use StateMachine\Domain\Exceptions\NoInitialStateException;
use StateMachine\Domain\StateMachine;
use StateMachine\Domain\Transition;

class StateMachineTest extends TestCase
{
    public function testStartWithoutStates()
    {
        $this->expectException(InvalidIntialStateMachine::class);
        $this->expectExceptionMessage('Invalid intial state machine "S0"');

        $machine = new StateMachine();
        $machine->start('S0');
    }

    public function testInvalidInitialStateMachine()
    {
        $this->expectException(InvalidIntialStateMachine::class);
        $this->expectExceptionMessage('Invalid intial state machine "S0"');

        $machine = new StateMachine();
        $machine->addState('S1', 1, true);
        $machine->start('S0');
    }

    public function testStateWithoutOutput()
    {
        $machine = new StateMachine();
        $machine->addState('S0', null, true);
        $machine->start('S0');
        $this->assertEquals('S0', $machine->currentStateName());
        $this->assertNull($machine->currentStateOutput());
    }

    public function testStateWithOutput()
    {
        $machine = new StateMachine();
        $machine->addState('S0', 1, true);
        $machine->start('S0');
        $this->assertEquals('S0', $machine->currentStateName());
        $this->assertEquals(1, $machine->currentStateOutput());
    }

    public function testAddTransitionsWithoutState()
    {
        $this->expectException(InvalidStateException::class);
        $this->expectExceptionMessage('"S0" is not a valid state.');

        $machine = new StateMachine();
        $machine->addTransition('S0', '1', 'S1');
    }

    public function testAddTransitionsWithoutAlphabet()
    {
        $this->expectException(InvalidTransitionAlphabetException::class);
        $this->expectExceptionMessage('"1" is not part of the alphabet');

        $machine = new StateMachine();
        $machine->addState('S0', '0', true);
        $machine->addTransition('S0', '1', 'S1');
    }

    public function testAddTransitionsWithInvalidAlphabet()
    {
        $this->expectException(InvalidTransitionAlphabetException::class);
        $this->expectExceptionMessage('"a" is not part of the alphabet');

        $machine = new StateMachine();
        $machine->addAlphabet(['1', '0']);
        $machine->addState('S0', '0', true);
        $machine->addTransition('S0', 'a', 'S1');
    }

    public function testAddTransition()
    {
        $machine = new StateMachine();
        $machine->addAlphabet(['1', '0']);
        $machine->addState('S0', '0', true);
        $machine->addTransition('S0', '1', 'S1');

        $state = $machine->states()['S0'];
        $this->assertEquals(['1' => Transition::create('S0', '1', 'S1')], $state->transitions());
    }

    public function testInvalidProcessInput()
    {
        $this->expectException(InvalidTransitionAlphabetException::class);
        $this->expectExceptionMessage('"a" is not part of the alphabet');

        $machine = new StateMachine();
        $machine->addAlphabet(['1', '0']);
        $machine->addState('S0', '0', true);
        $machine->addTransition('S0', '1', 'S1');
        $machine->start('S0');

        $machine->processInput('a');
    }

    public function testProcessInvalidNextStateInput()
    {
        $this->expectException(InvalidStateException::class);
        $this->expectExceptionMessage('"S1" is not a valid state.');

        $machine = new StateMachine();
        $machine->addAlphabet(['1', '0']);
        $machine->addState('S0', '0', true);
        $machine->addTransition('S0', '1', 'S1');
        $machine->start('S0');

        $machine->processInput('1');
    }

    public function testProcessInputIsNotString()
    {
        $this->expectException(InputNotStringException::class);
        $this->expectExceptionMessage('The input must be string');

        $machine = new StateMachine();
        $machine->processInput(1);
    }

    public function testProcessInputMachineNotStarted()
    {
        $this->expectException(NoInitialStateException::class);
        $this->expectExceptionMessage('Set the initial state for the machine');

        $machine = new StateMachine();

        $machine->addAlphabet(['1', '0']);
        $machine->addState('S0', '0', true);
        $machine->addState('S1', '1', true);
        $machine->addTransition('S0', '1', 'S1');

        $machine->processInput('1');

        $this->assertEquals('S1', $machine->currentStateName());
    }

    public function testProcessInput()
    {
        $machine = new StateMachine();

        $machine->addAlphabet(['1', '0']);
        $machine->addState('S0', '0', true);
        $machine->addState('S1', '1', true);
        $machine->addTransition('S0', '1', 'S1');
        $machine->start('S0');

        $machine->processInput('1');

        $this->assertEquals('S1', $machine->currentStateName());
        $this->assertTrue($machine->isValidFinal());
        $this->assertEquals('1', $machine->currentStateOutput());
    }
}
