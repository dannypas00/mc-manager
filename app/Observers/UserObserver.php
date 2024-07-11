<?php

namespace App\Observers;

use App\Events\UserUpdateEvent;
use App\Models\User;

class UserObserver
{
    public function updated(User $user): void
    {
        if ($user->isDirty()) {
            event(new UserUpdateEvent($user));
        }
    }
}
