<?php

namespace Tests\Unit\Services;

use App\Models\Server;
use App\Services\ServerConnectivityService;
use App\Services\ServerFilesystemStorageService;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use League\Flysystem\FilesystemException;
use League\Flysystem\Ftp\UnableToConnectToFtpHost;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use Storage;
use Tests\Traits\MocksServerConnectivityService;
use Tests\Traits\MockServerFilesystemStorageService;
use Tests\UnitTestCase;

#[CoversClass(ServerFilesystemStorageService::class)]
class ServerFileSystemStorageServiceTest extends UnitTestCase
{
    use MocksServerConnectivityService;
    use MockServerFilesystemStorageService;

    public function testGetFtp(): void
    {
        $this->mockServerConnectivityServiceGetFilesystem();

        app(ServerFilesystemStorageService::class)->getFtp(Server::factory()->makeOne());
    }

    public function testGetContents(): void
    {
        $ftp = $this->mockServerConnectivityServiceGetFilesystem();
        $ftp->put('testpath', 'testvalue');

        $response = app(ServerFilesystemStorageService::class)
            ->getContents(Server::factory()->makeOne(), 'testpath');

        $this->assertEquals('testvalue', $response);
    }

    public function testDeleteFile(): void
    {
        $ftp = $this->mockServerConnectivityServiceGetFilesystem();
        $ftp->makeDirectory('testdir');
        $ftp->put('testdir/testpath', 'testvalue');

        app(ServerFilesystemStorageService::class)
            ->delete(Server::factory()->makeOne(), 'testdir/testpath');

        Storage::assertMissing('testdir/testpath');
        Storage::assertExists('testdir');
    }

    public function testDeleteDirectory(): void
    {
        $ftp = $this->mockServerConnectivityServiceGetFilesystem();
        $ftp->makeDirectory('testdir');
        $ftp->put('testdir/testpath', 'testvalue');

        app(ServerFilesystemStorageService::class)
            ->delete(Server::factory()->makeOne(), 'testdir');

        Storage::assertMissing('testdir');
    }

    public function testListContentsRoot(): void
    {
        $ftp = $this->mockServerConnectivityServiceGetFilesystem();
        $ftp->put('testfile', 'testvalue');

        $this->mockFsGetDirectory(['test']);

        $result = app(ServerFilesystemStorageService::class)->listContents(Server::factory()->makeOne(), '');

        $this->assertEquals(['directories' => ['test']], $result);
    }

    public function testListContentsDirectory(): void
    {
        $ftp = $this->mockServerConnectivityServiceGetFilesystem();
        $ftp->put('testdir/testfile', 'testvalue');

        $this->mockFsGetDirectory(['test']);

        $result = app(ServerFilesystemStorageService::class)->listContents(Server::factory()->makeOne(), 'testdir');

        $this->assertEquals(['directories' => ['test']], $result);
    }

    public function testListContentsFiles(): void
    {
        $ftp = $this->mockServerConnectivityServiceGetFilesystem();
        $ftp->put('testfile', 'testvalue');

        $this->mockFsGetDirectory(['test']);

        $result = app(ServerFilesystemStorageService::class)->listContents(Server::factory()->makeOne(), 'testfile');

        $this->assertEquals(['file' => 'testfile'], $result);
    }

    public function testListContentsPathNotFound(): void
    {
        $this->mockServerConnectivityServiceGetFilesystem();

        $this->mockFsGetDirectory(['test']);

        $this->expectException(FileNotFoundException::class);
        app(ServerFilesystemStorageService::class)->listContents(Server::factory()->makeOne(), 'nonexistentpath');
    }

    public function testGetDirectory(): void
    {
        $ftp = $this->mockServerConnectivityServiceGetFilesystem();
        $ftp->put('shouldnotbelisted', 'empty');
        $ftp->put('testpath/a', 'testvalue a');
        $ftp->put('testpath/b/a', 'testvalue b');

        $result = app(ServerFilesystemStorageService::class)->getDirectory(Server::factory()->makeOne(), 'testpath');

        $this->assertEquals('dir', $result[0]['type']);
        $this->assertEquals('testpath/b', $result[0]['path']);

        $this->assertEquals('file', $result[1]['type']);
        $this->assertEquals('11', $result[1]['file_size']);
    }

    public function testGetDirectoryFailure(): void
    {
        $this->mock(
            ServerConnectivityService::class,
            static fn (MockInterface $mock) => $mock
                ->expects('getFilesystem')
                ->andThrow(new UnableToConnectToFtpHost())
        );

        $this->expectException(FilesystemException::class);
        app(ServerFilesystemStorageService::class)->getDirectory(Server::factory()->makeOne(), 'nonexistentpath');
    }

    public function testPut(): void
    {
        $ftp = $this->mockServerConnectivityServiceGetFilesystem();

        app(ServerFilesystemStorageService::class)->put(Server::factory()->makeOne(), 'testpath', 'testvalue');

        $ftp->assertExists('testpath', 'testvalue');
    }
}
