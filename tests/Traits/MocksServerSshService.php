<?php

namespace Tests\Traits;

use App\Services\ServerSshService;
use Mockery\MockInterface;

trait MocksServerSshService
{
    public function mockSshGetContent(?string $returns = null): void
    {
        $this->mock(
            ServerSshService::class,
            fn (MockInterface $mock) => $mock
                ->expects('getContents')
                ->andReturn($returns)
        )->makePartial();
    }

    public function mockSshDelete(): void
    {
        $this->mock(
            ServerSshService::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('delete')
                ->andReturn(true)
        )->makePartial();
    }

    public function mockSshListContents(array $returns = []): void
    {
        $this->mock(
            ServerSshService::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('listContents')
                ->andReturn($returns)
        )->makePartial();
    }

    public function mockSshPut(string ...$expectedPath): void
    {
        $this->mock(
            ServerSshService::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('put')
                ->withSomeOfArgs($expectedPath)
        )->makePartial();
    }
}
