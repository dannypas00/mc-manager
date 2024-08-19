<?php

namespace Tests\Traits;

use App\Enums\ServerStatus;
use App\Services\ServerConnectivityService;
use Illuminate\Contracts\Filesystem\Filesystem;
use Mockery\ExpectationInterface;
use Mockery\MockInterface;
use Storage;

trait MocksServerConnectivityService
{
    public function mockServerConnectivityServicePing(?array $returns = null): void
    {
        $this->mock(ServerConnectivityService::class, fn ($mock) => $this->serverConnectivityServicePingMock($mock, $returns));
    }

    public function serverConnectivityServicePingMock(MockInterface $mock, ?array $returns = null): ExpectationInterface
    {
        return $mock->expects('ping')->andReturn(
            $returns ??
            [
                'status'             => ServerStatus::Up,
                'version'            => ['name' => '1.20.2', 'protocol' => 764],
                'enforcesSecureChat' => true,
                'description'        => ['text' => 'test server'],
                'players'            => ['max' => 20, 'online' => 5]
            ]
        );
    }

    public function mockServerConnectivityServiceGetFilesystem(): Filesystem
    {
        $filesystem = Storage::fake();
        $this->mock(
            ServerConnectivityService::class,
            fn (MockInterface $mock) => $this->serverConnectivityServiceGetFilesystemMock($mock, $filesystem)
        );

        return $filesystem;
    }

    private function serverConnectivityServiceGetFilesystemMock(MockInterface $mock, Filesystem $filesystem): ExpectationInterface
    {
        return $mock
            ->expects('getFilesystem')
            ->andReturn($filesystem);
    }

    public function mockServerConnectivityServiceGetPlayers(array $returns = ['bob']): void
    {
        $this->mock(ServerConnectivityService::class, fn (MockInterface $mock) => $this->serverConnectivityServiceGetPlayersMock($mock));
    }

    public function serverConnectivityServiceGetPlayersMock(MockInterface $mock, array $returns = ['bob']): ExpectationInterface
    {
        return $mock->expects('getPlayers')->andReturn($returns);
    }

    public function mockServerConnectivityServiceGetEulaAcceptedStatus(bool $returns = true): void
    {
        $this->mock(
            ServerConnectivityService::class,
            fn (MockInterface $mock) => $this->serverConnectivityServiceGetEulaAcceptedStatusMock($mock, $returns)
        );
    }

    public function serverConnectivityServiceGetEulaAcceptedStatusMock(MockInterface $mock, bool $returns = true): ExpectationInterface
    {
        return $mock->expects('getEulaAcceptedStatus')->andReturn($returns);
    }

    public function mockServerConnectivityServiceGetRcon(): void
    {
        $this->mock(ServerConnectivityService::class, fn (MockInterface $mock) => $this->serverConnectivityServiceGetRconMock($mock));
    }

    public function serverConnectivityServiceGetRconMock(MockInterface $mock): ExpectationInterface
    {
        return $mock->expects('getRcon');
    }
}
