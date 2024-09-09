<?php

declare(strict_types=1);

namespace App\Http\Controllers\Storage;

use App\Exceptions\SshException;
use App\Http\Controllers\Controller;
use App\Repositories\Servers\FrontendServerShowRepository;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Inertia\Inertia;
use Inertia\Response;
use League\Flysystem\FilesystemException;

class StoragePathController extends Controller
{
    /**
     * @throws FilesystemException
     * @throws SshException
     */
    public function __invoke(
        FrontendServerShowRepository $showRepository,
        int $id,
        ?string $path = '',
    ): Response {
        $server = $showRepository->show($id);

        try {
            return Inertia::render('Servers/Files/ServerFiles', [
                'path' => $path ?? '',
                ...$server->storage_service->listContents($server, ltrim($path ?? '', '/'))
            ]);
        } catch (FileNotFoundException) {
            return Inertia::render('Servers/Files/ServerFiles', [
                'path'  => $path ?? '',
                'empty' => true,
            ]);
        }
    }
}
