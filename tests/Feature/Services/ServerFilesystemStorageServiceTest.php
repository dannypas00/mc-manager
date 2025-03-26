<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Models\Server;
use App\Services\ServerFilesystemStorageService;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
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

it('can delete', function (): void {
    expect($this->filesystem->exists('test.txt'))->toBeTrue()
        ->and($this->filesystem->exists('test-dir'))->toBeTrue()
        ->and($this->service->delete($this->server, 'test.txt'))->toBeTrue()
        ->and($this->service->delete($this->server, 'test-dir'))->toBeTrue()
        ->and($this->filesystem->exists('test.txt'))->toBeFalse()
        ->and($this->filesystem->exists('test-dir'))->toBeFalse();
});

it('can listContents', function (): void {

})->with([
    'file'      => ['path' => 'test.txt'],
    'directory' => ['path' => 'test-dir'],
]);

it('throws exception when file not found when listing', function (): void {
    expect(fn () => $this->service->listContents($this->server, 'not-found.txt'))->toThrow(FileNotFoundException::class, 'path_not_found');
});
