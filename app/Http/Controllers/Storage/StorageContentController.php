<?php

namespace App\Http\Controllers\Storage;

use App\Exceptions\SshException;
use App\Http\Requests\Storage\StorageContentRequest;
use App\Repositories\Servers\FrontendServerShowRepository;
use Illuminate\Http\JsonResponse;

class StorageContentController
{
    /**
     * @throws SshException
     */
    public function __invoke(
        int $serverId,
        StorageContentRequest $request,
        FrontendServerShowRepository $showRepository,
    ): JsonResponse {
        $server = $showRepository->show($serverId);

        return new JsonResponse([
            'content' => $server->storage_service->getContents($server, $request->get('path'))
        ]);
    }
}
