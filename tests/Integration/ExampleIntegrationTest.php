<?php

namespace Tests\Integration;

use File;
use Tests\IntegrationTestCase;

class ExampleIntegrationTest extends IntegrationTestCase
{
    public function testThatItStartsMinecraft(): void
    {
        $this->resetMinecraft();
        $this->assertTrue(File::exists(self::MINECRAFT_PATH . '/world'));
    }
}
