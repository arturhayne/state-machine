<?php

declare(strict_types=1);

namespace StateMachine\Domain\Exceptions;

class InputNotStringException extends \Exception
{
    protected $code = 404;

    public function __construct()
    {
        parent::__construct('The input must be string', $this->code);
    }
}
