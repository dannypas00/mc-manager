<?php

namespace Tests\Feature\Services;

use App\Models\Server;
use App\Services\ServerFilesystemStorageService;
use Storage;

covers(ServerFilesystemStorageService::class);

beforeEach(function () {
    $filesystem = Storage::fake();
    $filesystem->put('test.txt', 'test');
    $filesystem->put('test2.txt', 'test2');
    $filesystem->makeDirectory('test-dir');
    $filesystem->put('test-dir/test.txt', 'test');
    $this->service = app(ServerFilesystemStorageService::class);
    $this->server = Server::factory()->make();
    $this->server->setAttribute('filesystem', $filesystem);
});

test('it can get a file', function () {
    expect($this->service->getContents($this->server, 'test.txt'))->toEqual('test');
});
