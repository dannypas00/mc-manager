<?php

namespace App\Http\Controllers\Storage;

use App\Http\Requests\Storage\StorageContentRequest;
use App\Http\Requests\Storage\StorageListingRequest;
use App\Models\Server;
use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use League\Flysystem\FilesystemException;
use League\Flysystem\StorageAttributes;
use Log;
use Symfony\Component\HttpFoundation\Response;

class StorageContentController
{
    public function __invoke(int $serverId, StorageContentRequest $request): JsonResponse
    {
        $server = $this->getServer($serverId);
        return new JsonResponse(['content' => $server->ftp()->get($request->get('path'))]);
    }

    /**
     * @param int $serverId
     * @return Server
     */
    private function getServer(int $serverId): Server
    {
        try {
            return Server::whereHas(
                'users',
                static fn (Builder $user) => $user->where('id', Auth::user()->id)
            )->findOrFail($serverId);
        } catch (ModelNotFoundException) {
            abort(Response::HTTP_NOT_FOUND, 'no_server_found_for_user');
        }
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
