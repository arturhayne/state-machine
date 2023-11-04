<?php

declare(strict_types=1);

namespace StateMachine\Domain\Exceptions;

class MissingOutputException extends \Exception
{
    protected $code = 404;

    public function __construct($state)
    {
        parent::__construct(sprintf('There is no output for the state "%s".', $state), $this->code);
    }
}
