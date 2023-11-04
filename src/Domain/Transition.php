<?php

declare(strict_types=1);

namespace StateMachine\Domain;

class Transition
{
    private $source;
    private $input;
    private $destination;

    private function __construct($input, $destination, $source)
    {
        $this->input = $input;
        $this->destination = $destination;
        $this->source = $source;
    }

    public static function create($input, $destination, $source): self
    {
        return new self($input, $destination, $source);
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
