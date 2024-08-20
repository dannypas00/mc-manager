<?php

namespace Tests\Unit\Http\Controllers\Servers;

use App\Enums\ServerStatus;
use App\Enums\ServerType;
use App\Http\Controllers\Servers\ServerUpdateController;
use App\Models\Server;
use App\Services\ServerConnectivityService;
use App\Services\ServerSshService;
use Cache;
use Illuminate\Http\UploadedFile;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Traits\MocksFrontendServerShowRepository;
use Tests\Traits\MocksIconService;
use Tests\Traits\MocksServerConnectivityService;
use Tests\Traits\MocksServerSshService;
use Tests\Traits\MocksServerUpdateRepository;
use Tests\UnitTestCase;

#[CoversClass(ServerUpdateController::class)]
class ServerUpdateControllerTest extends UnitTestCase
{
    use MocksFrontendServerShowRepository;
    use MocksIconService;
    use MocksServerConnectivityService;
    use MocksServerSshService;
    use MocksServerUpdateRepository;

    private const ROUTE = 'api.servers.update';

    private function setupRequest(Server $server, int $expectedServerId = 1): void
    {
        $this->beUser();

        $this->mockFrontendServerShow($server, $expectedServerId);
    }

    public function testEnableFtpIsRequiredInManualType(): void
    {
        $this->setupRequest(Server::make(['type' => ServerType::Manual]));

        // enable_ftp is required when type is manual
        $this->putJson(route(self::ROUTE, ['id' => 1]))
            ->assertJsonValidationErrorFor('enable_ftp');
    }

    public function testEnableFtpIsNotRequiredInInstalledType(): void
    {
        $this->setupRequest(Server::make(['type' => ServerType::Installed]), 2);

        // enable_ftp is not required when type is installed
        $this->putJson(route(self::ROUTE, ['id' => 2]))
            ->assertJsonMissingValidationErrors(['enable_ftp']);
    }

    public function testInstalledServerCallsCorrectServices(): void
    {
        $attributes = [
            'name'              => 'test',
            'description'       => "test\ndescription",
            'icon'              => 'insert-uuid-here.jpg',
            'public_ip'         => 'example.com',
            'port'              => '25565',
            'rcon_port'         => '25575',
            'local_ip'          => 'localhost',
            'ssh_port'          => '22',
            'ssh_username'      => 'mcm-test',
            'ssh_key'           => 'ssh-key-here',
            'is_custom'         => false,
            'server_properties' => "pvp=false\n",
            'enable_ssh'        => true
        ];
        $data = array_merge($attributes, [
            'icon_file' => UploadedFile::fake()->image('icon.jpg'),
        ]);

        Cache::partialMock()->expects('remember')->andReturns([]);

        // Test filesystem
        $this->mockServerConnectivityServiceGetEulaAcceptedStatus();

        // Set provided server.properties
        // Ping ssh service
        $this->mock(
            ServerSshService::class,
            fn (MockInterface $mock) => $mock->allows(['put', 'ping'])
        );
        // Upload icon to icon storage
        $this->mockIconServiceStoreServerIcon('insert-uuid-here.jpg');

        $server = Server::factory()->makeOne(
            ['type' => ServerType::Installed, 'id' => 1, 'enable_ftp' => false, 'is_sftp' => false, 'use_ssh_auth' => false, 'status' => ServerStatus::Unknown] + $attributes
        );

        $this->setupRequest($server);

        // Update model values
        $this->mockServerUpdateRepositoryUpdate($server);

        $this->putJson(route(self::ROUTE, ['id' => 1]), $data)
            ->assertSuccessful();
    }

    public function testItShouldEnforceAuthentication(): void
    {
        $this->putJson(
            route(self::ROUTE, ['id' => 1])
        )->assertUnauthorized();
    }
}
