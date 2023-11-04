<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use StateMachine\Domain\Transition;

class TransitionsTest extends TestCase
{
    public function testCreateTransition()
    {
        $transition = Transition::create(0, 'S1', 'S0');

        $this->assertEquals('S1', $transition->destination());
        $this->assertEquals(0, $transition->input());
        $this->assertEquals('S1', $transition->destination());
        $this->assertEquals('S0', $transition->source());
    }
}
