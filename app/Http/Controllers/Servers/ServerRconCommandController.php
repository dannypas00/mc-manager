<?php

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Servers\ServerRconCommandRequest;
use App\Repositories\Servers\FrontendServerShowRepository;
use Illuminate\Http\JsonResponse;

class ServerRconCommandController extends Controller
{
    public function __invoke(int $serverId, ServerRconCommandRequest $request, FrontendServerShowRepository $showRepository): JsonResponse
    {
        $server = $showRepository->show($serverId);

        $response = $server->rcon()->send_command($request->get('command'));

        return new JsonResponse($response);
    }
}
