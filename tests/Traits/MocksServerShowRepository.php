<?php

namespace Tests\Traits;

use App\Models\Server;
use App\Repositories\Servers\ServerShowRepository;
use Mockery\MockInterface;

trait MocksServerShowRepository
{
    public function mockServerShowRepositoryFindOrFail(?Server $returns = null, int $expectedId = 1): void
    {
        $this->mock(
            ServerShowRepository::class,
            fn (MockInterface $mock) => $mock
                ->expects('findOrFail')
                ->withSomeOfArgs($expectedId)
                ->andReturn($returns ?? Server::factory()->makeOne())
        )->makePartial();
    }
}
