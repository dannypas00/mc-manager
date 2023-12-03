<?php

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Servers\ServerRconCommandRequest;
use App\Models\Server;
use App\Repositories\Servers\FrontendServerShowRepository;
use Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ServerRconCommandController extends Controller
{
    public function __invoke(ServerRconCommandRequest $request, FrontendServerShowRepository $showRepository): JsonResponse
    {
        $server = $showRepository->show($request->request->getInt('server_id'));

        $server->rcon()->send_command($request->get('command'));

        return new JsonResponse();
    }
}
