<?php

namespace Tests\Feature\Repositories\Servers;

use App\Models\Server;
use App\Repositories\Servers\ServerUpdateRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\FeatureTestCase;
use Tests\Traits\MocksServerConnectivityService;

#[CoversClass(ServerUpdateRepository::class)]
class ServerUpdateRepositoryTest extends FeatureTestCase
{
    use MocksServerConnectivityService;

    public function setUp(): void
    {
        parent::setUp();
        Server::flushEventListeners();
    }

    public function testUpdateByPing(): void
    {
        $this->mockServerConnectivityServicePing();
        $server = Server::factory(['description' => 'nah', 'current_players' => 0, 'maximum_players' => 0, 'version' => ''])->createOne();

        app(ServerUpdateRepository::class)->updateByPing($server);

        $server->refresh();

        $this->assertEquals('test server', $server->description);
        $this->assertEquals(5, $server->current_players);
        $this->assertEquals(20, $server->maximum_players);
        $this->assertEquals('1.20.2', $server->version);
    }

    public function testUpdateByPingNoData(): void
    {
        $this->mockServerConnectivityServicePing([]);
        $server = Server::factory()->makeOne();

        $result = app(ServerUpdateRepository::class)->updateByPing($server);

        $this->assertFalse($result);
    }
}
