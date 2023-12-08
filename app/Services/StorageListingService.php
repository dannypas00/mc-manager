<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Server;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Log;
use League\Flysystem\FilesystemException;
use League\Flysystem\StorageAttributes;

class StorageListingService
{
    /**
     * @throws FileNotFoundException
     * @throws FilesystemException
     */
    public function listContents(Server $server, string $path): array
    {
        $ftp = $server->ftp();

        if ($path === '' || $ftp->directoryExists($path)) {
            return ['directories' => $this->getDirectory($server, $path)];
        }

        if ($ftp->fileExists($path)) {
            return ['file' => $path];
        }

        throw new FileNotFoundException('path_not_found');
    }

    /**
     * @throws FilesystemException
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
            throw $e;
        }
    }
}
