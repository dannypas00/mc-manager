<?php

namespace Tests\Traits;

use App\Repositories\Servers\ServerUpdateRepository;
use App\Services\IconService;
use Mockery\MockInterface;

trait MocksIconService
{
    public function mockIconServiceStoreServerIcon(string $returns = 'test.jpg'): void
    {
        $this->mock(
            IconService::class,
            fn (MockInterface $mock) => $mock
                ->expects('storeServerIcon')
                ->andReturn($returns)
        )->makePartial();
    }

    public function mockIconServiceGetPublicUrl(string $returns = 'http://example.com/test.jpg'): void
    {
        $this->mock(
            IconService::class,
            fn (MockInterface $mock) => $mock
                ->expects('getPublicUrl')
                ->andReturn($returns)
        )->makePartial();
    }
}
