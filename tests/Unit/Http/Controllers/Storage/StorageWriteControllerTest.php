<?php

namespace Tests\Unit\Http\Controllers\Storage;

use App\Http\Controllers\Storage\StorageWriteController;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Traits\MocksFrontendServerShowRepository;
use Tests\Traits\MocksServerStorageService;
use Tests\UnitTestCase;

#[CoversClass(StorageWriteController::class)]
class StorageWriteControllerTest extends UnitTestCase
{
    use MocksFrontendServerShowRepository;
    use MocksServerStorageService;

    public function testItCallsStorageWrite(): void
    {
        $this->beUser();
        $this->mockFrontendServerShow();
        $this->mockServerStorageServicePut('testpath');

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
