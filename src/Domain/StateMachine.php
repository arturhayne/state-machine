<?php

declare(strict_types=1);

namespace StateMachine\Domain;

use StateMachine\Domain\Exceptions\InvalidIntialStateMachine;
use StateMachine\Domain\Exceptions\InvalidStateException;
use StateMachine\Domain\Exceptions\InvalidTransitionAlphabetException;
use StateMachine\Domain\Exceptions\MissingOutputException;

class StateMachine
{
    private $currentState;
    private $states;
    private $alphabet;

    public function __construct($alphabet, $states = [])
    {
        $this->alphabet = $alphabet;
        $this->states = $states;
    }

    public function createStates($acceptedStates, $finalStates, $outputMap)
    {
        $this->states = [];
        foreach ($acceptedStates as $state) {
            if (!array_key_exists($state, $outputMap) && in_array($state, $finalStates)) {
                throw new MissingOutputException($state);
            }
            $this->states[$state] = State::create(
                $state,
                $outputMap[$state] ?? null,
                in_array($state, $finalStates)
            );
        }
    }

    public function start($initialState)
    {
        if (!array_key_exists($initialState, $this->states)) {
            throw new InvalidIntialStateMachine($initialState);
        }
        $this->currentState = $this->states[$initialState];
    }

    public function addTransitionsToStates(array $transitions)
    {
        foreach ($transitions as $state => $stateTransitions) {
            if (!array_key_exists($state, $this->states)) {
                throw new InvalidStateException($state);
            }
            $stateObject = $this->states[$state];
            $stateObject->addTransitions($stateTransitions);
        }
    }

    public function processInput($input)
    {
        if (!in_array($input, $this->alphabet)) {
            throw new InvalidTransitionAlphabetException($input);
        }
        $state = $this->currentState->processInput($input);
        $this->currentState = $this->states[$state];
    }

    public function currentState()
    {
        return $this->currentState;
    }

    public function states()
    {
        return $this->states;
    }
}
