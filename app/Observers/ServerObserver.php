<?php

namespace App\Observers;

use App\Events\ServerUpdated;
use App\Models\Server;
use App\Services\IconService;

class ServerObserver
{
    public function updated(Server $server): void
    {
        if ($server->isDirty()) {
            event(new ServerUpdated($server));
        }

        $originalIcon = $server->getOriginal('icon');
        $newIcon = $server->icon;
        if ($originalIcon !== $newIcon) {
            app(IconService::class)->deleteIcon($originalIcon);
        }
    }
}
