<?php

namespace Tests\Traits;

use App\Models\Server;
use App\Repositories\Servers\ServerUpdateRepository;
use Mockery\MockInterface;

trait MocksServerUpdateRepository
{
    public function mockServerUpdateRepositoryUpdateByPing(bool $returns = true): void
    {
        $this->mock(
            ServerUpdateRepository::class,
            fn (MockInterface $mock) => $mock
                ->expects('updateByPing')
                ->andReturn($returns)
        )->makePartial();
    }

    public function mockServerUpdateRepositoryUpdate(Server $returns): void
    {
        $this->mock(
            ServerUpdateRepository::class,
            fn (MockInterface $mock) => $mock
                ->expects('update')
                ->andReturn($returns)
        )->makePartial();
    }
}
