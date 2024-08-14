<?php

namespace App\DataObjects;

use App\Enums\ServerStatus;
use App\Enums\ServerType;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
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
        public int $rcon_port,
        public bool $enable_ftp,
        public bool $is_sftp,
        public bool $use_ssh_auth,
        public ?int $ftp_port,
        public ?string $ftp_host,
        public ?string $ftp_username,
        public bool $enable_ssh,
        public ?string $ssh_username,
        public ?int $ssh_port,
        public int $current_players,
        public int $maximum_players,
        public string $name,
        public ?string $description,
        public ?string $version,
        public string $icon,
        public ?string $local_ip,
        public ?string $public_ip,
        public bool $has_accepted_eula,
        public array $player_list,
        public ?array $users,
        #[WithCast(DateTimeInterfaceCast::class)]
        public ?carbon $created_at,
        #[WithCast(DateTimeInterfaceCast::class)]
        public ?carbon $updated_at,
        public ?string $rcon_password = null,
        public ?string $ssh_key = null,
        public ?string $ftp_password = null,
    ) {
    }
}
