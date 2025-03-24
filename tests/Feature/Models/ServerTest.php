<?php

declare(strict_types=1);

use App\Models\Server;
use App\Rcon\Rcon;

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
