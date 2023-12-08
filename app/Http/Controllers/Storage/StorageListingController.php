<?php

namespace App\Http\Controllers\Storage;

use App\Http\Requests\Storage\StorageListingRequest;
use App\Models\Server;
use App\Repositories\Servers\FrontendServerShowRepository;
use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use League\Flysystem\FilesystemException;
use League\Flysystem\StorageAttributes;
use Log;
use Symfony\Component\HttpFoundation\Response;

class StorageListingController
{
    /**
     * @throws FilesystemException
     */
    public function __invoke(
        int $serverId,
        StorageListingRequest $request,
        FrontendServerShowRepository $showRepository
    ): JsonResponse {
        $server = $showRepository->show($serverId);
        $ftp = $server->ftp();

        // TODO: Use realpath() to tell frontend the actual directory
        // Not using request default because path can actually be null in request
        $path = $request->get('path') ?? '';

        if ($path === '' || $ftp->directoryExists($path)) {
            return new JsonResponse(['directories' => $this->getDirectory($server, $path)]);
        }

        if ($ftp->fileExists($path)) {
            return new JsonResponse(['file' => $path]);
        }

        return new JsonResponse(['error' => 'path_not_found'], Response::HTTP_NOT_FOUND);
    }

    /**
     * @param Server $server
     * @param string $storagePath
     * @return array
     */
    private function getDirectory(Server $server, string $storagePath): array
    {
        try {
            return $server->ftp()->listContents($storagePath)->map(
                static fn (StorageAttributes $entry) => $entry->jsonSerialize()
            )->toArray();
        } catch (FilesystemException $e) {
            Log::error(
                'Filesystem error while retrieving directory listing',
                ['path' => request()?->route(), 'exception' => $e->getTrace()]
            );
            abort(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
