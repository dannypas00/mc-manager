<?php

namespace Tests\Unit\Http\Controllers\Storage;

use App\Http\Controllers\Storage\StorageDeleteController;
use App\Models\Server;
use App\Services\ServerFilesystemStorageService;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\UnableToDeleteFile;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Traits\MocksFrontendServerShowRepository;
use Tests\UnitTestCase;

#[CoversClass(StorageDeleteController::class)]
class StorageDeleteControllerTest extends UnitTestCase
{
    use MocksFrontendServerShowRepository;

    private Filesystem $ftp;

    public function setUp(): void
    {
        parent::setUp();
        $this->ftp = Storage::fake();
        $this->ftp->makeDirectory('testdir');
        $this->ftp->put('testdir/testpath', 'testvalue');
        $this->ftp->put('testdir/testpath2', 'testvalue2');

        $this->ftp->makeDirectory('testdir2');
        $this->ftp->put('testdir2/testpath', 'testvalue2');
        $this->ftp->put('testdir2/testpath2', 'testvalue22');
    }

    public function testDeleteIsCalled(): void
    {
        $this->mockFrontendServerShow(Server::factory()->withFtp()->makeOne());

        $this->beUser();

        $this->mock(
            ServerFilesystemStorageService::class,
            fn (MockInterface $mock) => $mock
                ->expects('delete')
                ->withSomeOfArgs('testdir/testpath')
                ->once()
                ->andReturn(true)
        );

        $response = $this->deleteJson(
            route('api.servers.storage.delete', ['id' => 1]),
            ['paths' => ['testdir/testpath']]
        );

        $response->assertSuccessful();
    }

    public function testManyDeletesAreCalled(): void
    {
        $this->mockFrontendServerShow(Server::factory()->withFtp()->makeOne());

        $this->beUser();

        $this->mock(
            ServerFilesystemStorageService::class,
            fn (MockInterface $mock) => $mock
                ->expects('delete')
                ->times(5)
                ->andReturn(true)
        );

        $response = $this->deleteJson(
            route('api.servers.storage.delete', ['id' => 1]),
            ['paths' => ['testdir/testpath', 'testdir', 'testdir2/testpath', 'testdir2/testpath2', 'testdir2']]
        );

        $response->assertSuccessful();
    }

    public function testUnsuccessfulResponseOnException()
    {
        $this->mockFrontendServerShow(Server::factory()->withFtp()->makeOne());

        $this->beUser();

        $this->mock(
            ServerFilesystemStorageService::class,
            fn (MockInterface $mock) => $mock
                ->expects('delete')
                ->withSomeOfArgs('testdir/testpath')
                ->once()
                ->andThrow(new UnableToDeleteFile('test'))
        );

        $response = $this->deleteJson(
            route('api.servers.storage.delete', ['id' => 1]),
            ['paths' => ['testdir/testpath']]
        );

        $response->assertServerError();
        $response->assertJsonPath('failed_deleting', ['testdir/testpath']);
    }

    public function testItShouldEnforceAuthentication(): void
    {
        $this->deleteJson(
            route('api.servers.storage.delete', ['id' => 1]),
            ['paths' => ['testpath']]
        )->assertUnauthorized();
    }
}
