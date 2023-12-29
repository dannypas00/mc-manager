<?php

declare(strict_types=1);

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use App\Repositories\Servers\FrontendServerShowRepository;
use App\Services\ServerStorageService;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Inertia\Inertia;
use Inertia\Response;
use League\Flysystem\FilesystemException;

class StoragePathController extends Controller
{
    /**
     * @throws FileNotFoundException
     * @throws FilesystemException
     */
    public function __invoke(
        FrontendServerShowRepository $showRepository,
        ServerStorageService $storageService,
        int $id,
        ?string $path = '',
    ): Response {
        return Inertia::render('Servers/Files/ServerFiles', [
            'path' => $path ?? '',
            ...$storageService->listContents($showRepository->show($id), $path ?? '')
        ]);
    }
}
