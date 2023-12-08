<?php

declare(strict_types=1);

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use App\Repositories\Servers\FrontendServerShowRepository;
use App\Services\StorageListingService;
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
        StorageListingService $service,
        int $id,
        ?string $path = '',
    ): Response {
        return Inertia::render('Servers/Files/ServerFiles', [
            'path' => $path ?? '',
            ...$service->listContents($showRepository->show($id), $path ?? '')
        ]);
    }
}
