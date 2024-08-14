<?php

namespace Tests\Unit\Http\Controllers\Servers;

use App\Http\Controllers\Servers\ServerEulaAcceptedController;
use App\Models\Server;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Traits\MocksFrontendServerShowRepository;
use Tests\UnitTestCase;

#[CoversClass(ServerEulaAcceptedController::class)]
class ServerEulaAcceptedControllerTest extends UnitTestCase
{
    use MocksFrontendServerShowRepository;

    public function test_that_controller_returns_attribute(): void
    {
        $this->beUser();

        $server = Server::factory()->makeOne();
        $server->setAttribute('has_accepted_eula', true);
        $this->mockFrontendServerShow($server);

        $response = $this->getJson(route('api.servers.eula-accepted', ['id' => 1]));

        $response->assertSuccessful();
        $this->assertTrue($response->json());
    }

    public function testItShouldEnforceAuthentication(): void
    {
        $this->getJson(
            route('api.servers.eula-accepted', ['id' => 1])
        )->assertUnauthorized();
    }
}
