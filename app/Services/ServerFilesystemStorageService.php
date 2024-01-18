<?php

namespace App\Services;

use App\Models\Server;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\ArrayShape;
use League\Flysystem\FilesystemException;
use League\Flysystem\Ftp\FtpAdapter;
use League\Flysystem\StorageAttributes;

class ServerFilesystemStorageService implements ServerStorageServiceInterface
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

    public function delete(Server $server, string $path): ?bool
    {
        $ftp = $this->getFtp($server);

        if ($ftp->directoryExists($path)) {
            return $ftp->deleteDirectory($path);
        }

        return $ftp->delete($path);
    }

    #[ArrayShape([
        'directories' => StorageAttributes::class . '[]|null',
        'files'       => 'string|null'
    ])]
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
     *
     * @returns StorageAttributes[]
     */
    public function getDirectory(Server $server, string $storagePath): array
    {
        try {
            return $this->getFtp($server)
                ->listContents($storagePath)
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

    public function size(Server $server, string $path): int
    {
        return $this->getFtp($server)->size($path);
    }

    /**
     * @throws FilesystemException
     */
    public function tail(Server $server, string $path, int $offset): string
    {
        $stream = $this->getFtp($server)->readStream($path);
        $contents = stream_get_contents($stream, null, $offset);
        fclose($stream);

        return $contents;
    }

    public function append(Server $server, string $path, string $content): void
    {
        $this->getFtp($server)->append($path, $content, '');
    }
}
