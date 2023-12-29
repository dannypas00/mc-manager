<?php

namespace App\Repositories\Servers;

use App\Models\Server;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;

class FrontendServerShowRepository
{
    public function show(int $id): Server|Model
    {
        $server = Auth::user()?->servers()->find($id);

        if (!$server) {
            abort(Response::HTTP_NOT_FOUND, 'no_server_found_for_user');
        }

        return $server;
    }
}
