<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('users.{id}', static function ($user, $id) {
    return (int) Auth::id() === (int) $id;
});
