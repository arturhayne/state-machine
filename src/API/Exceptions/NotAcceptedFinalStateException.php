<?php

declare(strict_types=1);

namespace StateMachine\API\Exceptions;

class NotAcceptedFinalStateException extends \Exception
{
    protected $code = 404;

    public function __construct($state)
    {
        parent::__construct(sprintf('State "%s" is not accepted as a final state.', $state), $this->code);
    }
}
