<?php

declare(strict_types=1);

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorageDeleteRequest;
use App\Repositories\Servers\FrontendServerShowRepository;
use Illuminate\Http\JsonResponse;
use League\Flysystem\FilesystemException;
use Symfony\Component\HttpFoundation\Response;

class StorageDeleteController extends Controller
{
    /**
     * @throws FilesystemException
     */
    public function __invoke(
        int $serverId,
        StorageDeleteRequest $request,
        FrontendServerShowRepository $showRepository
    ): JsonResponse {
        $server = $showRepository->show($serverId);
        $ftp = $server->ftp();

        $paths = collect($request->get('paths', []));

        // First try to delete any directories, then delete all leftover paths
        $paths->filter(static fn (string $path) => $ftp->directoryExists($path))
            ->each(static fn (string $path) => $ftp->deleteDirectory($path));

        $ftp->delete($paths->toArray());

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
