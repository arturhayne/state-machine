<?php

declare(strict_types=1);

namespace StateMachine\Domain;

use StateMachine\Domain\Exceptions\InvalidStateInputException;

class State
{
    private $transitions;
    private $name;
    private $output;
    private $isValidFinal;

    private function __construct($name, $output, $isValidFinal, array $transitions)
    {
        $this->name = $name;
        $this->output = $output;
        $this->isValidFinal = $isValidFinal;
        $this->transitions = $transitions;
    }

    public static function create($name, $output, $isValidFinal): self
    {
        return new self($name, $output, $isValidFinal, []);
    }

    public function addTransitions(array $transitions)
    {
        $this->transitions = [];
        foreach ($transitions as $transition) {
            $this->transitions[$transition->input()] = $transition;
        }
    }

    public function addTransition(Transition $transition)
    {
        $this->transitions[$transition->input()] = $transition;
    }

    public function processInput($input)
    {
        if (!array_key_exists($input, $this->transitions)) {
            throw new InvalidStateInputException($input, $this->name);
        }

        return $this->transitions[$input]->destination();
    }

    public function output()
    {
        return $this->output;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function isValidFinal()
    {
        return $this->isValidFinal;
    }

    public function transitions()
    {
        return $this->transitions;
    }
}
