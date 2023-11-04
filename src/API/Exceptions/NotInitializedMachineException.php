<?php

declare(strict_types=1);

namespace StateMachine\API\Exceptions;

class NotInitializedMachineException extends \Exception
{
    protected $code = 404;

    public function __construct()
    {
        parent::__construct('State Machine not initialized.', $this->code);
    }
}
