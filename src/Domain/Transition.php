<?php

declare(strict_types=1);

namespace StateMachine\Domain;

use StateMachine\Domain\Exceptions\InputNotStringException;

class Transition
{
    private $source;
    private $input;
    private $destination;

    private function __construct($source, $input, $destination)
    {
        $this->destination = $destination;
        $this->source = $source;
        $this->setInput($input);
    }

    public static function create($source, $input, $destination): self
    {
        return new self($source, $input, $destination);
    }

    private function setInput($input)
    {
        if (!is_string($input)) {
            throw new InputNotStringException();
        }
        $this->input = $input;
    }

    public function destination()
    {
        return $this->destination;
    }

    public function input()
    {
        return $this->input;
    }

    public function source()
    {
        return $this->source;
    }
}
