<?php

namespace Tests\Unit\Http\Controllers\Servers;

use App\Enums\ServerType;
use App\Http\Controllers\Servers\ServerUpdateController;
use App\Models\Server;
use Illuminate\Http\UploadedFile;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use Str;
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
    use MocksServerConnectivityService;
    use MocksServerSshService;
    use MocksServerUpdateRepository;
    use MocksIconService;

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
            'icon'              => UploadedFile::fake()->image('icon.jpg'),
            'public_ip'         => 'example.com',
            'port'              => '25565',
            'rcon_port'         => '25575',
            'local_ip'          => 'localhost',
            'ssh_port'          => '22',
            'ssh_username'      => 'mcm-test',
            'ssh_key'           => 'ssh-key-here',
            'is_custom'         => false,
            'server_properties' => "pvp=false\n"
        ];
        $data = $attributes + [];

        $this->setupRequest(Server::make(['type' => ServerType::Installed]));
        // Test filesystem
        $this->mockServerConnectivityServiceGetFilesystem();
        // Set provided server.properties
        $this->mockSshPut('server.properties');
        // Upload icon to icon storage
        $this->mockIconServiceStoreServerIcon('insert-uuid-here.jpg');
        // RCON password generation
        $this->mock(
            Str::class,
            fn (MockInterface $mock) => $mock->shouldReceive('password')->andReturn('thisistotallyarandompassword')
        )->makePartial();


        // Update model values
        $this->mockServerUpdateRepositoryUpdate(expectedData: array_merge($attributes, [
            'icon'          => 'insert-uuid-here.jpg',
            'rcon_password' => 'thisistotallyarandompassword',
        ]));

        $this->putJson(route(self::ROUTE, ['id' => 1]), $data)
            ->assertValid()
            ->assertSuccessful();
    }

    public function testItShouldEnforceAuthentication(): void
    {
        $this->putJson(
            route(self::ROUTE, ['id' => 1])
        )->assertUnauthorized();
    }
}
