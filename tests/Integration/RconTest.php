<?php

declare(strict_types=1);

use App\Rcon\Rcon;

it('can send commands using RCON', function (): void {
    $rcon = new Rcon('minecraft', 25575, 'test1234', 30);

    $rcon->connect();
    $response = $rcon->send_command('list');
    $rcon->disconnect();

    expect($response)->toContain('players online');
});
