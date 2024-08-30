<?php

namespace Tests\Feature\Models;

use App\Exceptions\NoStorageServiceConfiguredException;
use App\Models\Server;
use App\Models\User;
use App\Services\ServerFilesystemStorageService;
use App\Services\ServerSshService;
use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\FeatureTestCase;
use Tests\Traits\MocksServerConnectivityService;

#[CoversClass(Server::class)]
class ServerTest extends FeatureTestCase
{
    use MocksServerConnectivityService;

    public function setUp(): void
    {
        parent::setUp();
        Server::flushEventListeners();
    }

    /**
     * @dataProvider encryptedAttributeProvider
     */
    public function testEncryptedAttribute(string $field, string $value): void
    {
        $server = Server::factory()->createOne();

        $server->$field = $value;
        $server->save();
        $server->refresh();

        // Assert that value stored in database is encrypted value
        $this->assertDatabaseHas('servers', [$field => $server->getRawOriginal($field)]);
        $this->assertDatabaseMissing('servers', [$field => $server->$field]);
        $this->assertEquals($value, $server->$field);
    }

    public static function encryptedAttributeProvider(): Generator
    {
        yield 'ftp_password' => [
            'field' => 'ftp_password',
            'value' => 'test1234',
        ];

        yield 'ftp_username' => [
            'field' => 'ftp_username',
            'value' => 'test1234',
        ];

        yield 'rcon_password' => [
            'field' => 'rcon_password',
            'value' => 'test1234',
        ];

        yield 'ssh_key' => [
            'field' => 'ssh_key',
            'value' => 'test1234',
        ];
    }

    /**
     * @dataProvider serviceAttributeProvider
     */
    public function testGetServiceAttribute(string $mock, string $attribute)
    {
        $server = Server::factory()->makeOne();
        $this->$mock();

        // Assertions are done by mock expectations
        $server->$attribute;
    }

    public static function serviceAttributeProvider(): Generator
    {
        yield 'rcon' => [
            'mock'      => 'mockServerConnectivityServiceGetRcon',
            'attribute' => 'rcon'
        ];
        yield 'ftp' => [
            'mock'      => 'mockServerConnectivityServiceGetFilesystem',
            'attribute' => 'ftp'
        ];
        yield 'player list' => [
            'mock'      => 'mockServerConnectivityServiceGetPlayers',
            'attribute' => 'player_list'
        ];
        yield 'eula accepted' => [
            'mock'      => 'mockServerConnectivityServiceGetEulaAcceptedStatus',
            'attribute' => 'has_accepted_eula'
        ];
    }

    public function testFilesystemServiceAttribute(): void
    {
        $server = Server::factory()->withFtp()->makeOne();

        $this->assertEquals(ServerFilesystemStorageService::class, $server->storage_service::class);
    }

    public function testSshServiceAttribute(): void
    {
        $server = Server::factory()->withSsh()->makeOne();

        $this->assertEquals(ServerSshService::class, $server->storage_service::class);
    }

    public function testUndefinedServiceAttribute(): void
    {
        $server = Server::factory()->makeOne();

        $this->expectException(NoStorageServiceConfiguredException::class);
        $server->storage_service;
    }

    public function testUsers()
    {
        $server = Server::factory()->createOne();

        // Assert server doesn't have users yet
        $this->assertDatabaseMissing('server_user', ['server_id' => $server->id]);

        // Attach users
        $users = User::factory()->count(5)->create();
        $server->users()->sync($users);

        // Assert server has users now
        $this->assertDatabaseHas('server_user', ['server_id' => $server->id]);
        $server->loadMissing(['users']);
        $this->assertCount(5, $server->users);

        // Remove users
        $server->users()->delete();
        $this->assertDatabaseMissing('server_user', ['server_id' => $server->id]);
        $server->refresh();
        $this->assertEmpty($server->users);
    }

    public function test_ssh_key_filled_attribute(): void
    {
        $server = Server::factory()->createOne(['ssh_key' => 'aaaa']);

        $this->assertTrue($server->is_ssh_key_filled);

        $server = Server::findOrFail($server->id);

        $this->assertTrue($server->is_ssh_key_filled);
    }
}
