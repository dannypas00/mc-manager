<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use PHPUnit\Framework\Attributes\CoversNothing;

#[CoversNothing]
class FeatureTestCase extends UnitTestCase
{
    use LazilyRefreshDatabase;
}
