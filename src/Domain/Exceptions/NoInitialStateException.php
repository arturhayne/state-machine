<?php

declare(strict_types=1);

namespace StateMachine\Domain\Exceptions;

class NoInitialStateException extends \Exception
{
    protected $code = 404;

    public function __construct()
    {
        parent::__construct('Set the initial state for the machine', $this->code);
    }
}
