<?php

namespace App\Http\Controllers\Storage;

use App\Http\Requests\Storage\StorageContentRequest;
use App\Http\Requests\Storage\StorageListingRequest;
use App\Models\Server;
use App\Repositories\Servers\FrontendServerShowRepository;
use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use League\Flysystem\FilesystemException;
use League\Flysystem\StorageAttributes;
use Log;
use Symfony\Component\HttpFoundation\Response;

class StorageContentController
{
    public function __invoke(int $serverId, StorageContentRequest $request, FrontendServerShowRepository $showRepository): JsonResponse
    {
        $server = $showRepository->show($serverId);
        return new JsonResponse(['content' => $server->ftp()->get($request->get('path'))]);
    }
}
