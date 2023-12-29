<?php

namespace Tests\Traits;

use App\Repositories\Servers\ServerUpdateRepository;
use Mockery\MockInterface;

trait MocksServerUpdateRepository
{
    public function mockServerShowRepositoryUpdateByPing(bool $returns = true): void
    {
        $this->mock(
            ServerUpdateRepository::class,
            fn (MockInterface $mock) => $mock
                ->expects('updateByPing')
                ->andReturn($returns)
        )->makePartial();
    }
}
