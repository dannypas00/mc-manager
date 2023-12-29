<?php

namespace Tests\Unit\Jobs;

use App\Enums\ServerStatus;
use App\Jobs\PollServerJob;
use App\Models\Server;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Traits\MocksServerConnectivityService;
use Tests\Traits\MocksServerModel;
use Tests\Traits\MocksServerShowRepository;
use Tests\Traits\MocksServerUpdateRepository;
use Tests\UnitTestCase;

#[CoversClass(PollServerJob::class)]
class PollServerJobTest extends UnitTestCase
{
    use MocksServerConnectivityService;
    use MocksServerModel;
    use MocksServerShowRepository;
    use MocksServerUpdateRepository;

    public function testPlayersGetZeroedWhenServerDown(): void
    {
        $server = Server::factory()->makeOne(['status' => ServerStatus::Down, 'current_players' => 10]);
        $this->mockServerShowRepositoryFindOrFail($server);
        $this->mockServerShowRepositoryUpdateByPing();
        dispatch_sync(new PollServerJob(1));

        $this->assertEquals(0, $server->current_players);
    }
}
