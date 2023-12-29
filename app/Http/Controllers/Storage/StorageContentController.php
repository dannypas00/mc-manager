<?php

namespace App\Http\Controllers\Storage;

use App\Http\Requests\Storage\StorageContentRequest;
use App\Repositories\Servers\FrontendServerShowRepository;
use Illuminate\Http\JsonResponse;

class StorageContentController
{
    public function __invoke(int $serverId, StorageContentRequest $request, FrontendServerShowRepository $showRepository): JsonResponse
    {
        $server = $showRepository->show($serverId);

        return new JsonResponse(['content' => $server->ftp()->get($request->get('path'))]);
    }
}
