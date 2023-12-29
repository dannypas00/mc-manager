<?php

namespace App\Http\Controllers\Storage;

use App\Http\Requests\Storage\StorageContentRequest;
use App\Repositories\Servers\FrontendServerShowRepository;
use App\Services\ServerStorageService;
use Illuminate\Http\JsonResponse;

class StorageContentController
{
    public function __invoke(
        int $serverId,
        StorageContentRequest $request,
        FrontendServerShowRepository $showRepository,
        ServerStorageService $storageService
    ): JsonResponse {
        $server = $showRepository->show($serverId);

        return new JsonResponse([
            'content' => $storageService->getContents($server, $request->get('path'))
        ]);
    }
}
