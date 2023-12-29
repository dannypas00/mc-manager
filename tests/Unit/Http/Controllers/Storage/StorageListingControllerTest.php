<?php

namespace Tests\Unit\Http\Controllers\Storage;

use App\Http\Controllers\Storage\StorageListingController;
use App\Services\ServerFilesystemStorageService;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use RuntimeException;
use Tests\Traits\MocksFrontendServerShowRepository;
use Tests\Traits\MocksServerStorageService;
use Tests\UnitTestCase;

#[CoversClass(StorageListingController::class)]
class StorageListingControllerTest extends UnitTestCase
{
    use MocksFrontendServerShowRepository;
    use MocksServerStorageService;

    public function testCallsStorageListing(): void
    {
        $this->beUser();
        $this->mockFrontendServerShow();
        $this->mockServerStorageServiceListContents(['files' => 'test']);

        $response = $this->getJson(route('api.servers.storage.listing', ['id' => 1]), ['path' => 'test']);

        $response->assertSuccessful();
    }

    public function testErrorResponseOnException(): void
    {
        $this->beUser();
        $this->mockFrontendServerShow();
        $this->mock(
            ServerFilesystemStorageService::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('listContents')
                ->andThrows(new RuntimeException('test'))
        )->makePartial();

        $response = $this->getJson(route('api.servers.storage.listing', ['id' => 1]), ['path' => 'test']);

        $response->assertNotFound();
        $response->assertJsonPath('error', 'test');
    }

    public function testItShouldEnforceAuthentication(): void
    {
        $this->getJson(
            route('api.servers.storage.listing', ['id' => 1]),
            ['path' => 'testpath']
        )->assertUnauthorized();
    }
}
