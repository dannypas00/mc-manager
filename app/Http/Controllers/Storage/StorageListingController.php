<?php

namespace App\Http\Controllers\Storage;

use App\Http\Requests\Storage\StorageListingRequest;
use App\Repositories\Servers\FrontendServerShowRepository;
use App\Services\StorageListingService;
use Illuminate\Http\JsonResponse;
use League\Flysystem\FilesystemException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class StorageListingController
{
    public function __invoke(
        int $serverId,
        StorageListingRequest $request,
        FrontendServerShowRepository $showRepository,
        StorageListingService $service,
    ): JsonResponse {
        $server = $showRepository->show($serverId);

        // TODO: Use realpath() to tell frontend the actual directory
        // Not using request default because path can actually be null in request
        $path = $request->get('path') ?? '';

        try {
            return new JsonResponse($service->listContents($server, $path));
        } catch (Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }
}
