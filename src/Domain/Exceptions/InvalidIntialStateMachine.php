<?php

declare(strict_types=1);

namespace StateMachine\Domain\Exceptions;

class InvalidIntialStateMachine extends \Exception
{
    protected $code = 404;

    public function __construct($state)
    {
        parent::__construct(sprintf('Invalid intial state machine "%s"', $state), $this->code);
    }
}
