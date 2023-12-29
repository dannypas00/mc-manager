<?php

namespace Tests\Unit\Http\Controllers\Storage;

use App\Http\Controllers\Storage\StorageContentController;
use App\Models\Server;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Traits\MocksFrontendServerShowRepository;
use Tests\Traits\MocksServerStorageService;
use Tests\UnitTestCase;

#[CoversClass(StorageContentController::class)]
class StorageContentControllerTest extends UnitTestCase
{
    use MocksFrontendServerShowRepository;
    use MocksServerStorageService;

    public function testItCallsStorageGet(): void
    {
        $server = Server::factory()->makeOne();

        $this->mockFrontendServerShow($server);
        $this->mockServeStorageServiceGetContents('testvalue');

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
