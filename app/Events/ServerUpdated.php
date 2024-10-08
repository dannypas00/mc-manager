<?php

namespace App\Events;

use App\Models\Server;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * @codeCoverageIgnore Event definition doesn't need testing
 */
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
     */
    public function broadcastOn(): Channel
    {
        return new Channel('servers.' . $this->server->id);
    }
}
