<?php

namespace Tests\Traits;

use App\Services\ServerStorageService;
use Illuminate\Contracts\Filesystem\Filesystem;
use Mockery\MockInterface;
use Storage;

trait MocksServerStorageService
{
    public function mockServeStorageServiceGetContents(?string $returns = null): void
    {
        $this->mock(
            ServerStorageService::class,
            fn (MockInterface $mock) => $mock
                ->expects('getContents')
                ->andReturn($returns)
        )->makePartial();
    }

    public function mockServerStorageServiceGetFtp(?Filesystem $returns = null): void
    {
        $this->mock(
            ServerStorageService::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('getFtp')
                ->andReturn($returns ?? Storage::fake())
        )->makePartial();
    }

    public function mockServerStorageServiceDelete(): void
    {
        $this->mock(
            ServerStorageService::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('delete')
                ->andReturn(true)
        )->makePartial();
    }

    public function mockServerStorageServiceListContents(array $returns = []): void
    {
        $this->mock(
            ServerStorageService::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('listContents')
                ->andReturn($returns)
        )->makePartial();
    }

    public function mockServerStorageServiceGetDirectory(array $returns = []): void
    {
        $this->mock(
            ServerStorageService::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('getDirectory')
                ->andReturn($returns)
        )->makePartial();
    }

    public function mockServerStorageServicePut(string $expectedPath): void
    {
        $this->mock(
            ServerStorageService::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('put')
                ->withSomeOfArgs($expectedPath)
        )->makePartial();
    }
}
