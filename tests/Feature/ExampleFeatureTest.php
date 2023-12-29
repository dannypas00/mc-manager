<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;

class ExampleFeatureTest extends FeatureTestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $this->beUser();

        $response = $this->get(route('home'));

        $response->assertStatus(200);
    }
}
