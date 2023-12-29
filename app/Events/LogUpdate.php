<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * @codeCoverageIgnore Event definition doesn't need testing
 */
class LogUpdate implements ShouldBroadcast
{
    use InteractsWithBroadcasting;

    /**
     * Create a new event instance.
     */
    public function __construct(public readonly string $text, public readonly int $lineNumber)
    {
    }

    public function broadcastAs(): string
    {
        return 'log-update';
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): Channel
    {
        return new Channel('log-message');
    }
}
