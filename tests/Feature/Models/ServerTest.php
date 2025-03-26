<?php

declare(strict_types=1);

use App\Models\Server;
use App\Models\User;
use App\Rcon\Rcon;
use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\Ftp\FtpAdapter;

it('returns a new rcon object', function (): void {
    $server = Server::factory()->make();

    expect($server->rcon)
        ->toBeInstanceOf(Rcon::class);
});

it('doesn\'t recreate the rcon object', function (): void {
    $server = Server::factory()->make();

    $rcon = $server->rcon;

    expect($server->rcon)
        ->toBe($rcon);
});

it('belongs to a user', function (): void {
    $user = User::factory()->create();
    $server = Server::factory()->create([
        'user_id' => $user->id,
    ]);

    expect($server->user)
        ->toBeInstanceOf(User::class)
        ->id->toEqual($user->id);
});

it('can create an ftp driver', function (): void {
    $server = Server::factory()->make();

    expect($server->filesystem)
        ->toBeInstanceOf(FilesystemAdapter::class)
        ->getAdapter()->toBeInstanceOf(FtpAdapter::class);
});
