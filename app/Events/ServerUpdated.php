<?php

namespace App\Events;

use App\Models\Server;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ServerUpdated implements ShouldBroadcast
{
    public function __construct(public readonly Server $server)
    {
    }

    public function broadcastAs(): string
    {
        return 'update';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn(): Channel
    {
        return new Channel('servers.' . $this->server->id);
    }
}
