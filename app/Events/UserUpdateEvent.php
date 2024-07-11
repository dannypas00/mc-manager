<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

readonly class UserUpdateEvent implements ShouldBroadcast
{
    public function __construct(public User $user)
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
        return new Channel('users.' . $this->user->id);
    }
}
