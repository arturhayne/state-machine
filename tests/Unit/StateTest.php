<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use StateMachine\Domain\Exceptions\InvalidStateInputException;
use StateMachine\Domain\State;
use StateMachine\Domain\Transition;

class StateTest extends TestCase
{
    public function testCreateState()
    {
        $transitions = [Transition::create('0', 'S0', 'S1'), Transition::create('1', 'S2', 'S1')];
        $state = State::create('S1', 1, true, $transitions);
        $this->assertEquals('S1', $state->name());
        $this->assertTrue($state->isValidFinal());
        $this->assertEquals(1, $state->output());
    }

    public function testProcessWrongInput()
    {
        $this->expectException(InvalidStateInputException::class);
        $this->expectExceptionMessage('Invalid input "2" for state "S1".');

        $transitions = [Transition::create('0', 'S0', 'S1'), Transition::create('1', 'S2', 'S1')];

        $state = State::create('S1', 1, true, $transitions);
        $state->processInput('2');
    }

    public function testProcessInput()
    {
        $transitions = [Transition::create('0', 'S0', 'S1'), Transition::create('1', 'S2', 'S1')];

        $state = State::create('S1', 1, true, $transitions);
        $this->assertEquals('S0', $state->processInput('0'));
    }
}
