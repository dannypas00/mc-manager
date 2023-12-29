<?php

namespace App\Services;

use App\Models\Server;
use JetBrains\PhpStorm\ArrayShape;
use League\Flysystem\StorageAttributes;
use Spatie\Ssh\Ssh;

class ServerSshStorageService implements ServerStorageServiceInterface
{
    private function getSsh(Server $server): Ssh
    {
        $privateKeyPath = 'php://memory';
        file_put_contents($privateKeyPath, $server->ssh_key);
        return Ssh::create($server->ssh_username, $server->local_ip)
            ->usePrivateKey($privateKeyPath)
            ->usePort($server->port)
            ->disableStrictHostKeyChecking();
    }

    public function getContents(Server $server, string $path): ?string
    {
        return $this->getSsh($server)
            ->execute("cat $path")
            ->getOutput();
    }

    public function delete(Server $server, string $path): ?bool
    {
        return $this->getSsh($server)
            ->execute("rm -rf $path")
            ->isSuccessful();
    }

    #[ArrayShape([
        'directories' => StorageAttributes::class . '[]|null',
        'files'       => 'string|null'
    ])]
    public function listContents(Server $server, string $path): array
    {
        dd($this->getSsh($server)->getExecuteCommand('echo test'));
        $ls = collect(
            explode(
                PHP_EOL,
                dd($this->getSsh($server)
                    ->execute("echo test")
                    ->getOutput())
            )
        );

        dd($ls);

        if (preg_match('/^total \d+$/', trim($ls->first()))) {
            return ['file' => $path];
        }

        $dirs = $ls->filter(static fn (string $line) => $line[0] === 'd')
            ->map(fn (string $line) => $this->mapLsToStorageAttributes($server, $line))
            ->toArray();

        return ['directories' => $dirs];
    }

    private function mapLsToStorageAttributes(Server $server, string $line): StorageAttributes
    {
        [, , $user, $group, $size, $date, $time, $permissions, $name] = explode(' ', $line);
        dd($user, $group, $size, $date, $time, $permissions, $name);
    }

    public function put(Server $server, string $path, string $content): void
    {
        // TODO: Implement put() method.
    }
}
