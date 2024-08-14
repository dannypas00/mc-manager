<?php

namespace Tests\Traits;

use App\Models\Server;
use App\Repositories\Servers\FrontendServerShowRepository;
use Mockery\MockInterface;

trait MocksFrontendServerShowRepository
{
    public function mockFrontendServerShow(?Server $returns = null, int $expectedId = 1): void
    {
        $this->mock(
            FrontendServerShowRepository::class,
            fn (MockInterface $mock) => $mock
                ->expects('show')
                ->withArgs([$expectedId])
                ->andReturn(
                    $returns ?? Server::factory()->makeOne(['id' => $expectedId])
                )
        );
    }
}
