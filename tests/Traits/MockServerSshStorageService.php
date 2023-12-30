<?php

namespace Tests\Traits;

use App\Services\ServerFilesystemStorageService;
use App\Services\ServerSshStorageService;
use Illuminate\Contracts\Filesystem\Filesystem;
use Mockery\MockInterface;
use Storage;

trait MockServerSshStorageService
{
    public function mockSshGetContent(?string $returns = null): void
    {
        $this->mock(
            ServerSshStorageService::class,
            fn (MockInterface $mock) => $mock
                ->expects('getContents')
                ->andReturn($returns)
        )->makePartial();
    }

    public function mockSshDelete(): void
    {
        $this->mock(
            ServerSshStorageService::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('delete')
                ->andReturn(true)
        )->makePartial();
    }

    public function mockSshListContents(array $returns = []): void
    {
        $this->mock(
            ServerSshStorageService::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('listContents')
                ->andReturn($returns)
        )->makePartial();
    }

    public function mockSshPut(string $expectedPath): void
    {
        $this->mock(
            ServerSshStorageService::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('put')
                ->withSomeOfArgs($expectedPath)
        )->makePartial();
    }
}
