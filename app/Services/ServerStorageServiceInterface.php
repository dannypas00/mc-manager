<?php

namespace App\Services;

use App\Models\Server;
use JetBrains\PhpStorm\ArrayShape;
use League\Flysystem\StorageAttributes;

interface ServerStorageServiceInterface
{
    /**
     * Get the contents of a file
     */
    public function getContents(Server $server, string $path): ?string;

    /**
     * Delete a file
     */
    public function delete(Server $server, string $path): ?bool;

    /**
     * List the contents of a directory or file location
     */
    #[ArrayShape([
        'directories' => StorageAttributes::class . '[]|null',
        'files'       => 'string|null'
    ])]
    public function listContents(Server $server, string $path): array;

    /**
     * Write contents to specified path
     */
    public function put(Server $server, string $path, string $content): void;
}
