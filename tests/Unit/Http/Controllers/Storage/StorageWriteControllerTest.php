<?php

namespace Tests\Unit\Http\Controllers\Storage;

use App\Http\Controllers\Storage\StorageWriteController;
use App\Models\Server;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Traits\MocksFrontendServerShowRepository;
use Tests\Traits\MockServerFilesystemStorageService;
use Tests\UnitTestCase;

#[CoversClass(StorageWriteController::class)]
class StorageWriteControllerTest extends UnitTestCase
{
    use MocksFrontendServerShowRepository;
    use MockServerFilesystemStorageService;

    public function testItCallsStorageWrite(): void
    {
        $this->beUser();
        $this->mockFrontendServerShow(Server::factory()->withFtp()->makeOne());
        $this->mockFsPut('testpath');

        $response = $this->postJson(route('api.servers.storage.write', ['id' => 1, 'path' => 'testpath']), ['content' => 'test']);

        $response->assertNoContent();
    }

    public function testItShouldEnforceAuthentication(): void
    {
        $this->postJson(
            route('api.servers.storage.write', ['id' => 1, 'path' => 'testpath'])
        )->assertUnauthorized();
    }
}
