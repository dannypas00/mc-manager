<?php

namespace Tests\Traits;

use App\Services\ServerFilesystemStorageService;
use Illuminate\Contracts\Filesystem\Filesystem;
use Mockery\MockInterface;
use Storage;

trait MockServerFilesystemStorageService
{
    public function mockFsGetContent(?string $returns = null): void
    {
        $this->mock(
            ServerFilesystemStorageService::class,
            fn (MockInterface $mock) => $mock
                ->expects('getContents')
                ->andReturn($returns)
        )->makePartial();
    }

    public function mockFsGetFtp(?Filesystem $returns = null): void
    {
        $this->mock(
            ServerFilesystemStorageService::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('getFtp')
                ->andReturn($returns ?? Storage::fake())
        )->makePartial();
    }

    public function mockFsDelete(): void
    {
        $this->mock(
            ServerFilesystemStorageService::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('delete')
                ->andReturn(true)
        )->makePartial();
    }

    public function mockFsListContents(array $returns = []): void
    {
        $this->mock(
            ServerFilesystemStorageService::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('listContents')
                ->andReturn($returns)
        )->makePartial();
    }

    public function mockFsGetDirectory(array $returns = []): void
    {
        $this->mock(
            ServerFilesystemStorageService::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('getDirectory')
                ->andReturn($returns)
        )->makePartial();
    }

    public function mockFsPut(string $expectedPath): void
    {
        $this->mock(
            ServerFilesystemStorageService::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('put')
                ->withSomeOfArgs($expectedPath)
        )->makePartial();
    }
}
