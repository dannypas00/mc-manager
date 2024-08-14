<?php

namespace App\Services;

use App\Exceptions\SshException;
use App\Models\Server;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use JetBrains\PhpStorm\ArrayShape;
use League\Flysystem\FilesystemException;
use League\Flysystem\StorageAttributes;

interface ServerStorageServiceInterface
{
    /**
     * Get the contents of a file
     *
     * @throws SshException
     */
    public function getContents(Server $server, string $path): ?string;

    /**
     * Delete a file
     *
     * @throws FilesystemException
     * @throws SshException
     */
    public function delete(Server $server, string $path): ?bool;

    /**
     * List the contents of a directory or file location
     *
     * @throws SshException
     * @throws FilesystemException
     * @throws FileNotFoundException
     */
    #[ArrayShape([
        'directories' => StorageAttributes::class . '[]|null',
        'files'       => 'string|null'
    ])]
    public function listContents(Server $server, string $path): array;

    /**
     * Write contents to specified path
     *
     * @throws SshException
     */
    public function put(Server $server, string $path, string $content): void;

    /**
     * Append content to the end of a file
     *
     * @throws SshException
     */
    public function append(Server $server, string $path, string $content): void;

    /**
     * Get size of file on filesystem
     *
     * @return int File size in bytes
     *
     * @throws SshException
     */
    public function size(Server $server, string $path): int;

    /**
     * @param  int  $offset Amount of bytes to skip from start of file
     *
     * @throws SshException
     * @throws FilesystemException
     */
    public function tail(Server $server, string $path, int $offset): string;
}
