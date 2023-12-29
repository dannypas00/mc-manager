<?php

declare(strict_types=1);

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorageDeleteRequest;
use App\Models\Server;
use App\Repositories\Servers\FrontendServerShowRepository;
use App\Services\ServerStorageService;
use Illuminate\Http\JsonResponse;
use League\Flysystem\FilesystemException;
use Symfony\Component\HttpFoundation\Response;

class StorageDeleteController extends Controller
{
    public function __invoke(
        int $serverId,
        StorageDeleteRequest $request,
        FrontendServerShowRepository $showRepository,
        ServerStorageService $storageService,
    ): JsonResponse {
        $server = $showRepository->show($serverId);
        $paths = collect($request->get('paths', []));
        $responseData = [];

        $paths->each(function (string $path) use ($storageService, $server, &$responseData) {
            $this->deletePath($storageService, $server, $path, $responseData);
        });

        return new JsonResponse(
            $responseData,
            filled($responseData) ? Response::HTTP_INTERNAL_SERVER_ERROR : Response::HTTP_NO_CONTENT
        );
    }

    private function deletePath(ServerStorageService $storageService, Server $server, string $path, array &$responseData): void
    {
        try {
            $storageService->delete($server, $path);
        } catch (FilesystemException $e) {
            report($e);
            $responseData['failed_deleting'][] = $path;
        }
    }
}
