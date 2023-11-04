<?php

declare(strict_types=1);

namespace StateMachine\Domain;

use StateMachine\Domain\Exceptions\InputNotStringException;
use StateMachine\Domain\Exceptions\InvalidIntialStateMachine;
use StateMachine\Domain\Exceptions\InvalidStateException;
use StateMachine\Domain\Exceptions\InvalidTransitionAlphabetException;
use StateMachine\Domain\Exceptions\NoInitialStateException;

class StateMachine
{
    private $currentState;
    private $states = [];
    private $alphabet = [];

    public function addAlphabet(array $alphabet)
    {
        $this->alphabet = $alphabet;
    }

    public function addState($name, $output, bool $isValidFinal)
    {
        return $this->states[$name] = State::create($name, $output, $isValidFinal);
    }

    public function addTransition($source, $input, $destination)
    {
        if (!array_key_exists($source, $this->states)) {
            throw new InvalidStateException($source);
        }

        if (!$this->alphabet || !in_array($input, $this->alphabet)) {
            throw new InvalidTransitionAlphabetException($input);
        }

        $transition = Transition::create($source, $input, $destination);
        $this->states[$source]->addTransition($transition);

        return $transition;
    }

    public function start($initialState)
    {
        if (!array_key_exists($initialState, $this->states)) {
            throw new InvalidIntialStateMachine($initialState);
        }
        $this->currentState = $this->states[$initialState];
    }

    public function processInput($input)
    {
        if (!is_string($input)) {
            throw new InputNotStringException();
        }

        if (!$this->currentState) {
            throw new NoInitialStateException();
        }

        for ($i = 0; $i < strlen($input); ++$i) {
            if (!in_array($input[$i], $this->alphabet)) {
                throw new InvalidTransitionAlphabetException($input[$i]);
            }
            $stateName = $this->currentState->processInput($input[$i]);
            if (!array_key_exists($stateName, $this->states)) {
                throw new InvalidStateException($stateName);
            }
            $this->currentState = $this->states[$stateName];
        }
    }

    public function currentStateName()
    {
        return $this->currentState->name();
    }

    public function currentStateOutput()
    {
        return $this->currentState->output();
    }

    public function isValidFinal()
    {
        return $this->currentState->isValidFinal();
    }

    public function states()
    {
        return $this->states;
    }
}
