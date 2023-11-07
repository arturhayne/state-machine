<?php

declare(strict_types=1);

namespace StateMachine\Domain\Exceptions;

class InvalidStateInputException extends \Exception
{
    protected $code = 404;

    public function __construct($input, $state)
    {
        parent::__construct(sprintf('Invalid input "%s" for state "%s".', $input, $state), $this->code);
    }
}
