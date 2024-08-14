<?php

namespace App\Repositories\Users;

use App\Models\User;
use Auth;

class AuthenticatedUserRepository
{
    public function getAuthenticatedUser(): ?User
    {
        return Auth::user()?->with(['servers'])->sole();
    }
}
