<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use StateMachine\Infra\ConfigurationManager;

class ConfigurationManagerTest extends TestCase
{
    public function testSetAndGetFinalStates()
    {
        ConfigurationManager::set('final-states', ['S1', 'S2']);

        $this->assertEquals(
            ['S1', 'S2'],
            ConfigurationManager::get('final-states')
        );
    }
}
