<?php

declare(strict_types=1);

namespace StateMachine\API;

use StateMachine\API\Exceptions\NotInitializedMachineException;
use StateMachine\Domain\StateMachine;

class StateMachineAPI
{
    private $stateMachine;

    /**
     * Create the state machine.
     */
    public function createMachine()
    {
        $this->stateMachine = new StateMachine();
    }

    /**
     * Add Alphabet to the state machine.
     */
    public function addAlphabet($alphabet)
    {
        if (!$this->stateMachine) {
            throw new NotInitializedMachineException();
        }

        $this->stateMachine->addAlphabet($alphabet);
    }

    /**
     * Add State to the state machine.
     */
    public function addState($name, $output, $isValidFinal)
    {
        if (!$this->stateMachine) {
            throw new NotInitializedMachineException();
        }

        return $this->stateMachine->addState($name, $output, $isValidFinal);
    }

    /**
     * Add transition to the state in the state machine.
     */
    public function addTransition($source, $input, $destination)
    {
        if (!$this->stateMachine) {
            throw new NotInitializedMachineException();
        }

        return $this->stateMachine->addTransition($source, $input, $destination);
    }

    /**
     * Set the initial state in the state machine.
     */
    public function startState($state)
    {
        if (!$this->stateMachine) {
            throw new NotInitializedMachineException();
        }

        $this->stateMachine->start($state);
    }

    /**
     * Process input a string of digits.
     */
    public function processInput($input)
    {
        if (!$this->stateMachine) {
            throw new NotInitializedMachineException();
        }

        $this->stateMachine->processInput($input);

        return $this->stateMachine->currentStateOutput();
    }

    /**
     * Return current state output.
     */
    public function output()
    {
        if (!$this->stateMachine) {
            throw new NotInitializedMachineException();
        }

        return $this->stateMachine->currentStateOutput();
    }

    /**
     * Check if the final state is accepted as final state.
     */
    public function isValidFinal()
    {
        if (!$this->stateMachine) {
            throw new NotInitializedMachineException();
        }

        return $this->stateMachine->isValidFinal();
    }
}
