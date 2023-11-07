<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use StateMachine\Domain\Exceptions\InputNotStringException;
use StateMachine\Domain\Transition;

class TransitionsTest extends TestCase
{
    public function testInputIsNotString()
    {
        $this->expectException(InputNotStringException::class);
        $this->expectExceptionMessage('The input must be string');

        Transition::create('S0', 0, 'S1');
    }

    public function testCreateTransition()
    {
        $transition = Transition::create('S0', '0', 'S1');

        $this->assertEquals('S1', $transition->destination());
        $this->assertEquals(0, $transition->input());
        $this->assertEquals('S1', $transition->destination());
        $this->assertEquals('S0', $transition->source());
    }
}
