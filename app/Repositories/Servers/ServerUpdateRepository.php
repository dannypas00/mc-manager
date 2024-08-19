<?php

namespace App\Repositories\Servers;

use App\Models\Server;
use App\Services\ServerConnectivityService;
use App\Traits\PadsArrayWithNull;

class ServerUpdateRepository
{
    use PadsArrayWithNull;

    public function updateByPing(Server $server): bool
    {
        $data = app(ServerConnectivityService::class)->ping($server);

        if (!$data) {
            return false;
        }

        $server->description = $data['description']['text'];
        $server->current_players = $data['players']['online'];
        $server->maximum_players = $data['players']['max'];
        $server->version = $data['version']['name'];

        return $server->save();
    }

    public function update(Server $server, array $data): bool
    {
        return $server->update($this->padArrayWithNull($server->getFillable(), $data));
    }
}
