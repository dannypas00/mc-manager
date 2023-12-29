<?php

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Servers\ServerRconCommandRequest;
use App\Repositories\Servers\FrontendServerShowRepository;
use Illuminate\Http\JsonResponse;

/**
 * @codeCoverageIgnore Mocking the RCON class seems like an impossible task after an hour of trying
 */
class ServerRconCommandController extends Controller
{
    public function __invoke(int $id, ServerRconCommandRequest $request, FrontendServerShowRepository $showRepository): JsonResponse
    {
        $server = $showRepository->show($id);

        $response = $server->rcon->send_command($request->get('command'));

        return new JsonResponse($response);
    }
}
