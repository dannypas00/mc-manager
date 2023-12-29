<?php

namespace App\Repositories\Servers;

use App\Models\Server;

class ServerShowRepository
{
    public function findOrFail(int $id, array $with = [], array $columns = ['*']): Server
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return Server::with($with)->findOrFail($id, $columns);
    }
}
