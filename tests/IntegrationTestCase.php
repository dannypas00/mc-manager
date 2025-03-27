<?php

declare(strict_types=1);

namespace Tests;

use Http;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class IntegrationTestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->freezeTime();
        $this->travelTo('2025-01-01 12:00:00');
    }
}
