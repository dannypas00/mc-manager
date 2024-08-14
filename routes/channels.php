<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('users.{id}', static function (User $user, $id) {
    return (int) Auth::id() === (int) $id;
});

Broadcast::channel('servers.{serverId}', static function (User $user, int $serverId) {
    // Verify that user actually owns server
    return $user->servers()->whereKey($serverId)->exists();
});
