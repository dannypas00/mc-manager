<?php

namespace App\Services;

use App\Exceptions\SshException;
use App\Models\Server;
use Carbon\Carbon;
use File;
use Illuminate\Contracts\Process\ProcessResult;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Process;
use JetBrains\PhpStorm\ArrayShape;
use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\StorageAttributes;
use Spatie\Ssh\Ssh;
use Str;
use Throwable;

/**
 * @codeCoverageIgnore Testing will be done in integration tests
 */
class ServerSshService implements ServerStorageServiceInterface
{
    private const BASE_PATH = '/minecraft';

    private function getSsh(Server $server, string $keyPath): Ssh
    {
        return Ssh::create($server->ssh_username, $server->local_ip)
            ->usePrivateKey($keyPath)
            ->usePort($server->ssh_port)
            ->disableStrictHostKeyChecking()
            ->setTimeout(5);
    }

    /**
     * @throws SshException
     */
    private function executeSsh(Server $server, string $command): ProcessResult
    {
        // TODO: This seems wildly unsafe, but I currently don't see any other option for how to do this without breaking open Spatie's package
        File::ensureDirectoryExists(storage_path('keys'));
        $keypath = storage_path('keys/' . Str::uuid()->toString());
        File::put($keypath, $server->ssh_key);

        try {
            File::chmod($keypath, 0600);

            $command = $this->getSsh($server, $keypath)->getExecuteCommand('cd ' . static::BASE_PATH . PHP_EOL . $command);
            $process = Process::run($command);

            File::delete($keypath);

            if (!$process->successful()) {
                throw new SshException($process->errorOutput(), $process->exitCode());
            }

            return $process;
        } catch (SshException $e) {
            throw $e;
        } catch (Throwable $e) {
            File::delete($keypath);
            throw new SshException('Unexpected exception whilst running ssh command', previous: $e);
        }
    }

    /**
     * This never returns false because any failure will trigger an exception, which is thrown to the frontend
     *
     * @param Server $server
     * @throws SshException
     * @return true
     */
    public function ping(Server $server): true
    {
        return $this->executeSsh($server, 'exit 0')->successful();
    }

    public function getContents(Server $server, string $path): ?string
    {
        return $this->executeSsh($server, "cat $path")
            ->output();
    }

    public function delete(Server $server, string $path): ?bool
    {
        return $this->executeSsh($server, "rm -rf $path")
            ->successful();
    }

    #[ArrayShape([
        'directories' => StorageAttributes::class . '[]|null',
        'files'       => 'string|null'
    ])]
    public function listContents(Server $server, ?string $path): array
    {
        if (empty($path)) {
            $path = '.';
        }
        $process = $this->executeSsh($server, "ls -lA --full-time $path");

        $ls = collect(
            explode(
                PHP_EOL,
                $process->output()
            )
        );

        if (!preg_match('/^total \d+$/', trim($ls->first()))) {
            return ['file' => $path];
        }

        $du = collect(explode(PHP_EOL, $this->executeSsh($server, "du --max-depth=1 $path")->output()));
        try {
            $mimes = collect(explode(PHP_EOL, $this->executeSsh($server, "file --mime-type $path/*")->output()));
        } catch (SshException $e) {
            // When file can't be executed (because it is not a GNU command and so not present on all systems), we stop trying to understand the mimetype
            // Instead we tell the user that every file is an application/octet-stream, because "The "octet-stream" subtype is used to indicate that a body contains arbitrary binary data."
            $mimes = collect();
        }

        $dirs = $ls
            ->filter()
            ->filter(static fn (string $line) => str_split($line)[0] === 'd' || str_split($line)[0] === '-')
            ->map(fn (string $line) => $this->mapLsToStorageAttributes($server, $line, $du, $mimes))
            ->toArray();

        return ['directories' => $dirs];
    }

    private function mapLsToStorageAttributes(Server $server, string $line, Collection $du, Collection $mimes): StorageAttributes
    {
        $line = preg_replace('/\s+/', ' ', $line);
        $type = str_split($line)[0] === 'd' ? StorageAttributes::TYPE_DIRECTORY : StorageAttributes::TYPE_FILE;
        [, , $user, $group, $size, $date, $time, $permissions, $name] = explode(' ', $line);

        if ($type === StorageAttributes::TYPE_DIRECTORY) {
            $duEntry = $du->first(static fn (string $duLine) => Str::contains($duLine, $name));
            $size = Str::before($duEntry, "\t");
        }

        $mime = Str::before(
            $mimes->first(static fn (string $mimeLine) => Str::contains($mimeLine, $name)) ?? 'application/octet-stream',
            "\t"
        );

        $attributes = [
            StorageAttributes::ATTRIBUTE_PATH          => Str::after($name, './'),
            StorageAttributes::ATTRIBUTE_FILE_SIZE     => (int)$size,
            StorageAttributes::ATTRIBUTE_LAST_MODIFIED => Carbon::parse("$date $time")->timestamp,
            StorageAttributes::ATTRIBUTE_MIME_TYPE     => Str::afterLast($mime, ' '),
            StorageAttributes::ATTRIBUTE_TYPE          => $type,
        ];

        return $type === StorageAttributes::TYPE_DIRECTORY
            ? DirectoryAttributes::fromArray($attributes)
            : FileAttributes::fromArray($attributes);
    }

    /**
     * @throws SshException
     */
    public function put(Server $server, string $path, string $content): void
    {
        $this->executeSsh($server, "echo '$content' > ./$path");
    }

    /**
     * @throws SshException
     */
    public function size(Server $server, string $path): int
    {
        return (int)Str::before($this->executeSsh($server, "wc -c $path")->output(), ' ');
    }

    /**
     * @throws SshException
     */
    public function tail(Server $server, string $path, int $offset): string
    {
        return $this->executeSsh($server, "tail --bytes=+$offset $path")->output();
    }

    public function append(Server $server, string $path, string $content): void
    {
        $encoded = base64_encode($content);
        $this->executeSsh($server, "echo \"$encoded\" | base64 --decode >> $path");
    }
}
