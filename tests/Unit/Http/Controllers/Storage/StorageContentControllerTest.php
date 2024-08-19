<?php

namespace Tests\Unit\Http\Controllers\Storage;

use App\Http\Controllers\Storage\StorageContentController;
use App\Models\Server;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Traits\MockServerFilesystemStorageService;
use Tests\Traits\MocksServerSshService;
use Tests\Traits\MocksFrontendServerShowRepository;
use Tests\UnitTestCase;

#[CoversClass(StorageContentController::class)]
class StorageContentControllerTest extends UnitTestCase
{
    use MockServerFilesystemStorageService;
    use MocksServerSshService;
    use MocksFrontendServerShowRepository;

    public function testItCallsStorageGetFilesystem(): void
    {
        $server = Server::factory()->withFtp()->makeOne();

        $this->mockFrontendServerShow($server);
        $this->mockFsGetContent('testvalue');

        $this->beUser();

        $response = $this->getJson(
            route('api.servers.storage.content', ['id' => 1, 'path' => 'testpath']),
            ['path' => 'testpath']
        );

        $response->assertJson(['content' => 'testvalue']);
    }

    public function testItCallsStorageGetSsh(): void
    {
        $server = Server::factory()->withSsh()->makeOne();

        $this->mockFrontendServerShow($server);
        $this->mockSshGetContent('testvalue');

        $this->beUser();

        $response = $this->getJson(
            route('api.servers.storage.content', ['id' => 1, 'path' => 'testpath']),
            ['path' => 'testpath']
        );

        $response->assertJson(['content' => 'testvalue']);
    }

    public function testItShouldEnforceAuthentication(): void
    {
        $this->getJson(
            route('api.servers.storage.content', ['id' => 1, 'path' => 'testpath'])
        )->assertUnauthorized();
    }
}
