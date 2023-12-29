<?php

namespace Tests\Traits;

use App\Enums\ServerStatus;
use App\Services\ServerConnectivityService;
use Illuminate\Contracts\Filesystem\Filesystem;
use Mockery\MockInterface;
use Storage;

trait MocksServerConnectivityService
{
    public function mockServerConnectivityServicePing(?array $returns = null): void
    {
        $this->mock(
            ServerConnectivityService::class,
            fn (MockInterface $mock) => $mock
                ->expects('ping')
                ->andReturn(
                    $returns ??
                    [
                        'status'             => ServerStatus::Up,
                        'version'            => ['name' => '1.20.2', 'protocol' => 764],
                        'enforcesSecureChat' => true,
                        'description'        => ['text' => 'test server'],
                        'players'            => ['max' => 20, 'online' => 5]
                    ]
                )
        )->makePartial();
    }

    public function mockServerConnectivityServiceGetFilesystem(): Filesystem
    {
        $filesystem = Storage::fake();
        $this->mock(
            ServerConnectivityService::class,
            static fn (MockInterface $mock) => $mock
                ->expects('getFilesystem')
                ->andReturn($filesystem)
        );

        return $filesystem;
    }

    public function mockServerConnectivityServiceGetPlayers(array $returns = ['bob']): void
    {
        $this->mock(
            ServerConnectivityService::class,
            static fn (MockInterface $mock) => $mock
                ->expects('getPlayers')
                ->andReturn($returns)
        );
    }

    public function mockServerConnectivityServiceGetEulaAcceptedStatus(bool $returns = true): void
    {
        $this->mock(
            ServerConnectivityService::class,
            static fn (MockInterface $mock) => $mock
                ->expects('getEulaAcceptedStatus')
                ->andReturn($returns)
        );
    }

    public function mockServerConnectivityServiceGetRcon(): void
    {
        $this->mock(
            ServerConnectivityService::class,
            static fn (MockInterface $mock) => $mock
                ->expects('getRcon')
        );
    }
}
