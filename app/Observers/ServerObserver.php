<?php

namespace App\Observers;

use App\Events\ServerUpdated;
use App\Models\Server;

class ServerObserver
{
    public function updated(Server $server): void
    {
        if ($server->isDirty()) {
            event(new ServerUpdated($server));
        }
    }
}
