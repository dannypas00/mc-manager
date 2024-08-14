<?php

declare(strict_types=1);

namespace App\Http\Controllers\Storage;

use App\Exceptions\SshException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorageDeleteRequest;
use App\Models\Server;
use App\Repositories\Servers\FrontendServerShowRepository;
use Illuminate\Http\JsonResponse;
use League\Flysystem\FilesystemException;
use Symfony\Component\HttpFoundation\Response;

class StorageDeleteController extends Controller
{
    public function __invoke(
        int $serverId,
        StorageDeleteRequest $request,
        FrontendServerShowRepository $showRepository,
    ): JsonResponse {
        $server = $showRepository->show($serverId);
        $paths = collect($request->get('paths', []));
        $responseData = [];

        $paths->each(function (string $path) use ($server, &$responseData) {
            $this->deletePath($server, $path, $responseData);
        });

        return new JsonResponse(
            $responseData,
            filled($responseData) ? Response::HTTP_INTERNAL_SERVER_ERROR : Response::HTTP_NO_CONTENT
        );
    }

    private function deletePath(Server $server, string $path, array &$responseData): void
    {
        try {
            $server->storage_service->delete($server, $path);
        } catch (FilesystemException|SshException $e) {
            report($e);
            $responseData['failed_deleting'][] = $path;
        }
    }
}
