<?php

namespace App\Repositories\Servers;

use App\Models\Server;
use App\Services\ServerConnectivityService;
use App\Traits\PadsArrayWithNull;
use Arr;

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

    public function update(Server $server, array $data): Server
    {
        // Don't fill the hidden fields with null, since they won't be set to previous values in the frontend
        $fillable = Arr::except($server->getFillable(), $server->getHidden() + ['icon']);

        $server->update($this->padArrayWithNull($fillable, $data));
        $server->refresh();

        return $server;
    }
}
