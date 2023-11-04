<?php

declare(strict_types=1);

namespace StateMachine\API;

use StateMachine\API\Exceptions\InputNotStringException;
use StateMachine\API\Exceptions\NotAcceptedFinalStateException;
use StateMachine\API\Exceptions\NotInitializedMachineException;
use StateMachine\Domain\StateMachine;
use StateMachine\Infra\ConfigurationManager;

class StateMachineAPI
{
    private $stateMachine;

    public function __construct()
    {
        ConfigurationManager::load();
    }

    /**
     * Create the state machine encapsulated.
     */
    public function createMachine()
    {
        $this->stateMachine = new StateMachine(ConfigurationManager::get('alphabet'));
        $this->stateMachine->createStates(
            ConfigurationManager::get('states'),
            ConfigurationManager::get('final-states'),
            ConfigurationManager::get('output-map')
        );
        $this->stateMachine->start(ConfigurationManager::get('initial-state'));
        $this->stateMachine->addTransitionsToStates(ConfigurationManager::get('transitions'));
    }

    /**
     * Process input a string of digits.
     */
    public function processInput($input)
    {
        if (!is_string($input)) {
            throw new InputNotStringException();
        }

        if (!$this->stateMachine) {
            throw new NotInitializedMachineException();
        }

        for ($i = 0; $i < strlen($input); ++$i) {
            $this->stateMachine->processInput($input[$i]);
        }

        if (!$this->stateMachine->currentState()->isValidFinal()) {
            throw new NotAcceptedFinalStateException($this->currentState());
        }

        return $this->stateMachine->currentState()->output();
    }

    /**
     * Process Input informing step by step interaction.
     */
    public function processInputDebuging($input)
    {
        if (!is_string($input)) {
            throw new InputNotStringException();
        }

        if (!$this->stateMachine) {
            throw new NotInitializedMachineException();
        }

        for ($i = 0; $i < strlen($input); ++$i) {
            $currentStateBeforeInput = $this->currentState();
            $this->stateMachine->processInput($input[$i]);
            echo sprintf(
                'Current state = %s, Input = %s, result state = %s',
                $currentStateBeforeInput,
                $input[$i],
                $this->currentState()
            ) . "\n";
        }
        echo "No more input \n";

        if (!$this->stateMachine->currentState()->isValidFinal()) {
            throw new NotAcceptedFinalStateException($this->currentState());
        }

        return sprintf('output for state %s = %s', $this->currentState(), $this->output()) . "\n";
    }

    /**
     * Create machine, execute input and return ouput with one single command.
     */
    public function execute($input)
    {
        $this->createMachine();
        $this->processInput($input);

        return $this->output();
    }

    /**
     * Return current state output.
     */
    public function output()
    {
        if (!$this->stateMachine) {
            throw new NotInitializedMachineException();
        }

        return $this->stateMachine->currentState()->output();
    }

    /**
     * Return current state name.
     */
    public function currentState()
    {
        if (!$this->stateMachine) {
            throw new NotInitializedMachineException();
        }

        return $this->stateMachine->currentState()->name();
    }
}
