<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Models\Server;
use App\Services\ServerFilesystemStorageService;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use League\Flysystem\FileAttributes;
use League\Flysystem\StorageAttributes;
use Mockery;
use Mockery\MockInterface;
use Storage;

covers(ServerFilesystemStorageService::class);

beforeEach(function (): void {
    $this->filesystem = Storage::fake();
    $this->filesystem->put('test.txt', 'test');
    $this->filesystem->put('test2.txt', 'test2');
    $this->filesystem->makeDirectory('test-dir');
    $this->filesystem->put('test-dir/test.txt', 'test');
    $this->service = \Pest\Laravel\mock(ServerFilesystemStorageService::class,
        fn (MockInterface $mock) => $mock->expects('getFtp')->zeroOrMoreTimes()->andReturns($this->filesystem))
        ->makePartial();
    $this->server = Server::factory()->make();
    $this->server->setAttribute('filesystem', $this->filesystem);
});

it('can getContents', function (): void {
    expect($this->service->getContents($this->server, 'test.txt'))->toEqual('test');
});

it('can delete', function (string $path): void {
    expect($this->filesystem->exists($path))->toBeTrue()
        ->and($this->service->delete($this->server, $path))->toBeTrue()
        ->and($this->filesystem->exists($path))->toBeFalse();
})->with([
    'directory' => 'test-dir',
    'file'      => 'test.txt',
]);

it('can list a file', function (): void {
    expect($this->service->listContents($this->server, 'test.txt'))->toMatchArray(['file' => 'test.txt']);
});

it('can list a directory', function (): void {
    expect($this->service->listContents($this->server, 'test-dir')['directories'][0])
        ->toBeInstanceOf(FileAttributes::class)
        ->type()->toBe('file')
        ->path()->toBe('test-dir/test.txt')
        ->fileSize()->toBe(4)
        ->visibility()->toBe('public')
        ->lastModified()->toBeNumeric();
});

it('throws exception when file not found when listing', function (): void {
    expect(fn () => $this->service->listContents($this->server, 'not-found.txt'))->toThrow(FileNotFoundException::class, 'path_not_found');
});
