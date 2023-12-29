<?php

namespace App\Services;

use App\Models\Server;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Log;
use League\Flysystem\FilesystemException;
use League\Flysystem\Ftp\FtpAdapter;
use League\Flysystem\StorageAttributes;

class ServerStorageService
{
    public function getFtp(Server $server): FilesystemAdapter|FtpAdapter
    {
        return app(ServerConnectivityService::class)->getFilesystem($server);
    }

    public function getContents(Server $server, string $path): ?string
    {
        return $this->getFtp($server)
            ->get($path);
    }

    /**
     * @throws FilesystemException
     */
    public function delete(Server $server, string $path): ?bool
    {
        $ftp = $this->getFtp($server);

        if ($ftp->directoryExists($path)) {
            return $ftp->deleteDirectory($path);
        }

        return $ftp->delete($path);
    }

    /**
     * @throws FileNotFoundException
     * @throws FilesystemException
     */
    public function listContents(Server $server, string $path): array
    {
        $ftp = $this->getFtp($server);

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
    public function getDirectory(Server $server, string $storagePath): array
    {
        try {
            return $this->getFtp($server)
                ->listContents($storagePath)
                ->map(static fn (StorageAttributes $entry) => $entry->jsonSerialize())
                ->toArray();
        } catch (FilesystemException $e) {
            Log::error(
                'Filesystem error while retrieving directory listing',
                ['path' => request()?->route(), 'exception' => $e->getTrace()]
            );
            throw $e;
        }
    }

    public function put(Server $server, string $path, string $content): void
    {
        $this->getFtp($server)->put($path, $content);
    }
}
