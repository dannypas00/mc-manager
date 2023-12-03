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
    public function __invoke(int $serverId, StorageListingRequest $request, FrontendServerShowRepository $showRepository): JsonResponse
    {
        $server = $showRepository->show($serverId);
        // TODO: Use realpath() to tell frontend the actual directory
        return new JsonResponse($this->getDirectories($server, $request->get('path') ?? ''));
    }

    /**
     * @param Server $server
     * @param string $storagePath
     * @return array
     */
    private function getDirectories(Server $server, string $storagePath): array
    {
        try {
            $server->ftp()->path('world/..');
            return $server->ftp()->listContents($storagePath)->map(static fn (StorageAttributes $entry) => $entry->jsonSerialize())->toArray();
        } catch (FilesystemException $e) {
            Log::error(
                'Filesystem error while retrieving directory listing',
                ['path' => request()?->route(), 'exception' => $e->getTrace()]
            );
            abort(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
