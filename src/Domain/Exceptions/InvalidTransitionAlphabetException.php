<?php

declare(strict_types=1);

namespace StateMachine\Domain\Exceptions;

class InvalidTransitionAlphabetException extends \Exception
{
    protected $code = 404;

    public function __construct($input)
    {
        parent::__construct(sprintf('"%s" is not part of the alphabet', $input), $this->code);
    }
}
