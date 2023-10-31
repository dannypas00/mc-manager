<?php

namespace App\Repositories\Servers;

use App\Models\Server;
use Auth;
use Illuminate\Database\Eloquent\Builder;

class FrontendServerShowRepository
{
    public function show(int $id): Server
    {
        return Server::whereHas(
            'user',
            static fn (Builder $user) => $user->whereKey(Auth::user()->id)
        )->findOrFail($id);
    }
}
