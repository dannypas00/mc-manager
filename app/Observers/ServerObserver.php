<?php

namespace App\Observers;

use App\Events\ServerUpdated;
use App\Models\Server;

class ServerObserver
{
    /**
     * Handle the Server "created" event.
     */
    public function created(Server $server): void
    {
        //
    }

    /**
     * Handle the Server "updated" event.
     */
    public function updated(Server $server): void
    {
        if ($server->isDirty()) {
            event(new ServerUpdated($server));
        }
    }

    /**
     * Handle the Server "deleted" event.
     */
    public function deleted(Server $server): void
    {
        //
    }

    /**
     * Handle the Server "restored" event.
     */
    public function restored(Server $server): void
    {
        //
    }

    /**
     * Handle the Server "force deleted" event.
     */
    public function forceDeleted(Server $server): void
    {
        //
    }
}
