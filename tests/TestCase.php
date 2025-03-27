<?php

declare(strict_types=1);

namespace Tests;

use Carbon\Carbon;
use Http;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();
        $this->freezeTime();
        $this->travelTo('2025-01-01 12:00:00');
    }
}
