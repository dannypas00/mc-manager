<?php

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;
use App\Repositories\Servers\FrontendServerShowRepository;
use Illuminate\Http\JsonResponse;

class ServerEulaAcceptedController extends Controller
{
    public function __invoke(int $serverId, FrontendServerShowRepository $showRepository): JsonResponse
    {
        $server = $showRepository->show($serverId);

        return new JsonResponse($server->has_accepted_eula);
    }
}
