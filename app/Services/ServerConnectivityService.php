<?php

namespace App\Services;

use App\Enums\ServerStatus;
use App\Models\Server;
use App\Rcon\Rcon;
use Illuminate\Filesystem\FilesystemAdapter;
use JetBrains\PhpStorm\ArrayShape;
use Log;
use Storage;
use Str;
use Throwable;
use xPaw\MinecraftPing;
use xPaw\MinecraftPingException;
use xPaw\MinecraftQuery;
use xPaw\MinecraftQueryException;

/**
 * To be replaced with strategy pattern to accommodate for missing connectivity
 * e.g. ftp over ssh or missing query settings
 *
 * @codeCoverageIgnore This should really be tested in integration testing, so ignoring it for the unit tests
 */
class ServerConnectivityService
{
    #[ArrayShape([
        'status'             => ServerStatus::class,
        'version'            => ['name' => 'string', 'protocol' => 'int'],
        'enforcesSecureChat' => 'bool',
        'description'        => ['text' => 'string'],
        'players'            => ['max' => 'int', 'online' => 'int']
    ])]
    public function ping(Server $server): array
    {
        $ping = (new MinecraftPing($server->local_ip, $server->port));

        $data = $this->tryPingStatus($ping);
        $ping->Close();

        return $data;
    }

    private function tryPingStatus(MinecraftPing $ping): array
    {
        try {
            /**
             * False response means server is responding but doesn't have data for us yet
             * Array response means server is responding as it should
             * Any other type of response (such as null) is treated as unknown
             * Exception is thrown here when server can't be reached
             */
            $response = $ping->Query();

            if ($response === false) {
                return ['status' => ServerStatus::Starting];
            }

            if (is_array($response)) {
                return ['status' => ServerStatus::Up, ...$response];
            }

            return ['status' => ServerStatus::Unknown];
        } catch (MinecraftPingException) {
            return ['status' => ServerStatus::Down];
        }
    }

    public function query(Server $server): MinecraftQuery
    {
        $query = new MinecraftQuery;
        $query->Connect($server->local_ip, $server->port);

        return $query;
    }

    public function getPlayers(Server $server): array
    {
        try {
            $players = $this->query($server)->GetPlayers();
            if (!$players) {
                return [];
            }

            return $players;
        } catch (MinecraftQueryException $e) {
            Log::error(
                'Exception getting player list',
                ['exception' => $e::class, 'message' => $e->getMessage(), 'trace' => $e->getTrace()]
            );

            return [];
        }
    }

    public function getEulaAcceptedStatus(Server $server): bool
    {
        try {
            return Str::isMatch('/.*eula=true.*/', $server->storage_service->getContents($server, 'eula.txt'));
        } catch (Throwable $e) {
            Log::warning(
                'Error thrown when retrieving EULA',
                ['exception' => $e::class, 'message' => $e->getMessage(), 'trace' => $e->getTrace()]
            );

            return true;
        }
    }

    public function getFilesystem(Server $server): FilesystemAdapter
    {
        $config = $this->getFilesystemConfig($server);

        return $server->is_sftp
            ? Storage::createSftpDriver($config)
            : Storage::createFtpDriver($config);
    }

    private function getFilesystemConfig(Server $server)
    {
        $baseConfig = [
            'driver' => $server->is_sftp ? 'sftp' : 'ftp',
            'host'   => $server->ftp_host,
            'port'   => $server->ftp_port,
        ];

        if ($server->use_ssh_auth) {
            return $baseConfig + [
                    'privateKey' => $server->ftp_username,
                    'passphrase' => $server->ftp_password,
                ];
        }

        return $baseConfig + [
                'username' => $server->ftp_username,
                'password' => $server->ftp_password,
            ];
    }

    public function getRcon(Server $server): Rcon|false
    {
        $rcon = new Rcon(
            $server->local_ip,
            $server->rcon_port,
            $server->rcon_password,
            30
        );

        return $rcon->connect() ? $rcon : false;
    }
}
