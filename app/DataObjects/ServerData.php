<?php

namespace App\DataObjects;

use App\Enums\ServerStatus;
use App\Enums\ServerType;
use App\Models\Server;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use DateTime;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class ServerData extends Data
{
    /**
     * @param array<UserData>|null $users
     */
    public function __construct(
        public int $id,
        public bool $enabled,
        public ServerType $type,
        public ServerStatus $status,
        public int $port,
        public int $rconPort,
        public bool $enableFtp,
        public bool $isSftp,
        public bool $useSshAuth,
        public ?int $ftpPort,
        public ?string $ftpHost,
        public ?string $ftpUsername,
        public bool $enableSsh,
        public ?string $sshUsername,
        public ?int $sshPort,
        public int $currentPlayers,
        public int $maximumPlayers,
        public string $name,
        public ?string $description,
        public ?string $version,
        public string $icon,
        public ?string $localIp,
        public ?string $publicIp,
        public bool $hasAcceptedEula,
        public array $playerList,
        public ?array $users,
        #[WithCast(DateTimeInterfaceCast::class)]
        public ?Carbon $created_at,
        #[WithCast(DateTimeInterfaceCast::class)]
        public ?Carbon $updated_at,
        public ?string $rconPassword = null,
        public ?string $sshKey = null,
        public ?string $ftpPassword = null,
    ) {
    }
}
