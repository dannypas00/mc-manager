<?php

namespace Tests\Traits;

use App\Models\Server;
use Mockery\MockInterface;

trait MocksServerModel
{
    public function mockServerModelSave(): void
    {
        $this->mock(
            Server::class,
            fn (MockInterface $mock) => $mock
                ->expects('save')
        )->makePartial();
    }

    public function mockServerModelRefresh(): void
    {
        $this->mock(
            Server::class,
            fn (MockInterface $mock) => $mock
                ->expects('refresh')
        )->makePartial();
    }
}
