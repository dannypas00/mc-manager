<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Models\Server;
use App\Services\ServerFilesystemStorageService;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use League\Flysystem\FileAttributes;
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

it('can getContents', function (string $path, ?string $result): void {
    expect($this->service->getContents($this->server, $path))->toEqual($result);
})->with([
    'file'       => ['path' =>'test.txt', 'result' => 'test'],
    'nonesitent' => ['path' =>'not-found.txt', 'result' => null],
    'nested'     => ['path' =>'test-dir/test.txt', 'result' => 'test'],
]);

it('can delete', function (string $path): void {
    expect($this->filesystem->exists($path))->toBeTrue()
        ->and($this->service->delete($this->server, $path))->toBeTrue()
        ->and($this->filesystem->exists($path))->toBeFalse();
})->with([
    'directory' => 'test-dir',
    'file'      => 'test.txt',
]);

describe('listContents', function (): void {
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
});

describe('getDirectory', function (): void {
    it('can get a directory', function (): void {
        expect($this->service->getDirectory($this->server, 'test-dir')[0])
            ->toBeInstanceOf(FileAttributes::class)
            ->type()->toBe('file')
            ->path()->toBe('test-dir/test.txt')
            ->fileSize()->toBe(4)
            ->visibility()->toBe('public')
            ->lastModified()->toBeNumeric();
    });

    it('returns empty array on file not found', function (): void {
        expect($this->service->getDirectory($this->server, 'not-found'))->toBeArray()->toBeEmpty();
    });
});

describe('put', function (): void {
    it('can put file', function (): void {
        $this->service->put($this->server, 'test.txt', 'test');
        expect($this->filesystem->get('test.txt'))->toBe('test');
    });

    it('can overwrite with put', function (): void {
        $this->service->put($this->server, 'test.txt', 'test');
        $this->service->put($this->server, 'test.txt', 'test2');
        expect($this->filesystem->get('test.txt'))->toBe('test2');
    });
});

it('can get size', function (): void {
    expect($this->service->size($this->server, 'test.txt'))->toBe(4);
});

it('can tail a file', function (): void {
    expect($this->service->tail($this->server, 'test.txt', 2))->toBe('st');
});

it('can append to a file', function (): void {
    $this->service->append($this->server, 'test.txt', '2');
    expect($this->filesystem->get('test.txt'))->toBe('test2');
});
