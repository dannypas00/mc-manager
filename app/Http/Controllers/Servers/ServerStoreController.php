<?php

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Servers\ServerStoreRequest;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\UnauthorizedException;
use Str;

class ServerStoreController extends Controller
{
    public function __invoke(ServerStoreRequest $request): RedirectResponse
    {
        $user = Auth::user();
        if (!$user) {
            throw new UnauthorizedException('Unauthenticated user attempting to create server');
        }

        $data = $request->validated();
        $data['rcon_password'] = Str::password();
        $server = $user->servers()->create($data);

        return redirect(route('servers.edit', ['id' => $server->id]));
    }
}
