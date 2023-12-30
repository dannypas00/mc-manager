<?php

declare(strict_types=1);

namespace App\Http\Controllers\Storage;

use App\Exceptions\SshException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorageWriteRequest;
use App\Repositories\Servers\FrontendServerShowRepository;
use App\Services\ServerFilesystemStorageService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * TODO: This is currently most likely an unsafe controller since it allows anyone with access to write any file the ftp has access to
 * Solution to this problem and whether it is actually a problem is unclear for now.
 */
class StorageWriteController extends Controller
{
    /**
     * @throws SshException
     */
    public function __invoke(
        int $serverId,
        string $path,
        StorageWriteRequest $request,
        FrontendServerShowRepository $showRepository,
    ): JsonResponse {
        $server = $showRepository->show($serverId);
        $server->storage_service->put($server, $path, $request->get('content'));

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
