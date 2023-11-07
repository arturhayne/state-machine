<?php

declare(strict_types=1);

namespace StateMachine\Domain\Exceptions;

class InvalidStateException extends \Exception
{
    protected $code = 404;

    public function __construct($state)
    {
        parent::__construct(sprintf('"%s" is not a valid state.', $state), $this->code);
    }
}
