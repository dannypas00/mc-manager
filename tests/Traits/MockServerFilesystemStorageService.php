<?php

namespace Tests\Traits;

use App\Services\ServerFilesystemStorageService;
use App\Services\ServerStorageServiceInterface;
use Illuminate\Contracts\Filesystem\Filesystem;
use Mockery\MockInterface;
use Storage;

trait MockServerFilesystemStorageService
{
    private MockInterface|ServerStorageServiceInterface $fsMock;

    public function getFsMock(): MockInterface
    {
        if (!isset($this->fsMock)) {
            $this->fsMock = $this->mock(ServerFilesystemStorageService::class);
        }

        return $this->fsMock;
    }

    public function mockFsGetContent(?string $returns = null): void
    {
        $this->getFsMock()
            ->expects('getContents')
            ->andReturn($returns);
    }

    public function mockFsGetFtp(?Filesystem $returns = null): void
    {
        $this->getFsMock()
            ->shouldReceive('getFtp')
            ->andReturn($returns ?? Storage::fake());
    }

    public function mockFsDelete(): void
    {
        $this->getFsMock()
            ->shouldReceive('delete')
            ->andReturn(true);
    }

    public function mockFsListContents(array $returns = []): void
    {
        $this->getFsMock()
            ->shouldReceive('listContents')
            ->andReturn($returns);
    }

    public function mockFsGetDirectory(array $returns = []): void
    {
        $this->getFsMock()
            ->shouldReceive('getDirectory')
            ->andReturn($returns);
    }

    public function mockFsPut(string $expectedPath): void
    {
        $this->getFsMock()
            ->shouldReceive('put')
            ->withSomeOfArgs($expectedPath);
    }

    public function mockFsSize(string $expectedPath, int $returns): void
    {
        $this->getFsMock()
            ->shouldReceive('size')
            ->withSomeOfArgs($expectedPath)
            ->andReturn($returns);
    }

    public function mockFsTail(string $expectedPath, string $returns): void
    {
        $this->getFsMock()
            ->shouldReceive('tail')
            ->withSomeOfArgs($expectedPath)
            ->andReturn($returns);
    }
}
