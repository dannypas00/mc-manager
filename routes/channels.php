<?php

use App\Models\User;

Broadcast::channel('servers.{serverId}', static function (User $user, int $serverId) {
    // Verify that user actually owns server
    return $user->servers()->whereKey($serverId)->exists();
});
