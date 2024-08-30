<?php

namespace App\Repositories\Servers;

use App\Models\Server;
use App\Services\ServerConnectivityService;
use App\Traits\PadsArrayWithNull;
use Log;

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
        $fillableData = $this->getOnlyPaddedFillable(
            $server->getFillable(),
            $data,
            ['icon', 'type'],
            ['enable_ftp', 'enable_ssh', 'is_sftp', 'use_ssh_auth', 'ssh_key', 'ftp_password', 'rcon_password']
        );

        Log::debug('Filling model', ['fillData' => $fillableData]);

        foreach ($fillableData as $key => $value) {
            $server->$key = $value;
        }

        $server->save();

        Log::debug('Server', ['server' => $server->toArray(), 'data' => $fillableData]);

        return $server;
    }
}
