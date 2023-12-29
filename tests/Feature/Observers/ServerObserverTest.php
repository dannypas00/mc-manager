<?php

namespace Tests\Feature\Observers;

use App\Events\ServerUpdated;
use App\Models\Server;
use App\Observers\ServerObserver;
use Event;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\FeatureTestCase;

#[CoversClass(ServerObserver::class)]
class ServerObserverTest extends FeatureTestCase
{
    public function testUpdated(): void
    {
        Event::fake(ServerUpdated::class);

        $server = Server::factory(['name' => 'not_test'])->createOne();
        $server->update(['name' => 'test']);

        Event::assertDispatchedTimes(ServerUpdated::class, 1);
    }
}
