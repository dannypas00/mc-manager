<?php

namespace App\Models;

use App\Enums\ServerStatus;
use App\Exceptions\NoStorageServiceConfiguredException;
use App\Observers\ServerObserver;
use App\Rcon\Rcon;
use App\Services\ServerConnectivityService;
use App\Services\ServerFilesystemStorageService;
use App\Services\ServerSshStorageService;
use App\Services\ServerStorageServiceInterface;
use Cache;
use Crypt;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Carbon;
use League\Flysystem\Ftp\FtpAdapter;

/**
 * App\Models\Server
 *
 * @property int $id
 * @property int $enabled
 * @property ServerStatus $status
 * @property int $port
 * @property int $rcon_port
 * @property int $enable_ftp
 * @property int $is_sftp
 * @property int $use_ssh_auth
 * @property int|null $ftp_port
 * @property string|null $ftp_host
 * @property string|null $ftp_username Contains private key when using ssh key auth
 * @property string|null $ftp_password Contains pass phrase when using ssh key auth
 * @property string|null $ssh_username
 * @property string|null $ssh_port
 * @property string|null $ssh_key
 * @property int $current_players
 * @property int $maximum_players
 * @property string $name
 * @property string|null $description
 * @property string|null $version
 * @property string $icon
 * @property string|null $local_ip
 * @property string $public_ip
 * @property string $rcon_password
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \League\Flysystem\Ftp\FtpAdapter|\Illuminate\Filesystem\FilesystemAdapter $ftp
 * @property-read bool $has_accepted_eula
 * @property-read array $player_list
 * @property-read \App\Rcon\Rcon|false $rcon
 * @property-read \App\Services\ServerStorageServiceInterface $storage_service
 * @property-read Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 *
 * @method static \Database\Factories\ServerFactory factory($count = null, $state = [])
 * @method static Builder|Server newModelQuery()
 * @method static Builder|Server newQuery()
 * @method static Builder|Server query()
 * @method static Builder|Server whereCreatedAt($value)
 * @method static Builder|Server whereCurrentPlayers($value)
 * @method static Builder|Server whereDescription($value)
 * @method static Builder|Server whereEnableFtp($value)
 * @method static Builder|Server whereEnabled($value)
 * @method static Builder|Server whereFtpHost($value)
 * @method static Builder|Server whereFtpPassword($value)
 * @method static Builder|Server whereFtpPort($value)
 * @method static Builder|Server whereFtpUsername($value)
 * @method static Builder|Server whereIcon($value)
 * @method static Builder|Server whereId($value)
 * @method static Builder|Server whereIsSftp($value)
 * @method static Builder|Server whereLocalIp($value)
 * @method static Builder|Server whereMaximumPlayers($value)
 * @method static Builder|Server whereName($value)
 * @method static Builder|Server wherePort($value)
 * @method static Builder|Server wherePublicIp($value)
 * @method static Builder|Server whereRconPassword($value)
 * @method static Builder|Server whereRconPort($value)
 * @method static Builder|Server whereSshKey($value)
 * @method static Builder|Server whereSshPort($value)
 * @method static Builder|Server whereSshUsername($value)
 * @method static Builder|Server whereStatus($value)
 * @method static Builder|Server whereUpdatedAt($value)
 * @method static Builder|Server whereUseSshAuth($value)
 * @method static Builder|Server whereVersion($value)
 *
 * @mixin Eloquent
 */
class Server extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'enabled',
        // Connection info
        'local_ip',
        'public_ip',
        'port',
        'rcon_port',
        'rcon_password',
        // Cached info
        'current_players',
        'maximum_players',
        'player_list',
        'status',
        // FTP settings
        'enable_ftp',
        'is_sftp',
        'use_ssh_auth',
        'ftp_port',
        'ftp_host',
        'ftp_username',
        'ftp_password',
    ];

    protected $casts = [
        'status' => ServerStatus::class,
    ];

    // Attributes

    public function rconPassword(): Attribute
    {
        return Attribute::make(
            get: static fn (string $value): string => Crypt::decrypt($value),
            set: static fn (string $value): string => Crypt::encrypt($value),
        );
    }

    public function ftpUsername(): Attribute
    {
        return Attribute::make(
            get: static fn (?string $value): ?string => $value ? Crypt::decrypt($value) : null,
            set: static fn (?string $value): ?string => $value ? Crypt::encrypt($value) : null,
        );
    }

    public function ftpPassword(): Attribute
    {
        return Attribute::make(
            get: static fn (?string $value): ?string => $value ? Crypt::decrypt($value) : null,
            set: static fn (?string $value): ?string => $value ? Crypt::encrypt($value) : null,
        );
    }

    public function sshKey(): Attribute
    {
        return Attribute::make(
            get: static fn (?string $value): ?string => $value ? Crypt::decrypt($value) : null,
            set: static fn (?string $value): ?string => $value ? Crypt::encrypt($value) : null,
        );
    }

    public function getRconAttribute(): Rcon|false
    {
        return app(ServerConnectivityService::class)->getRcon($this);
    }

    public function getFtpAttribute(): FtpAdapter|FilesystemAdapter
    {
        return app(ServerConnectivityService::class)->getFilesystem($this);
    }

    public function getPlayerListAttribute(): array
    {
        return Cache::remember(
            $this->id . '-server-player-list',
            10,
            static fn (): array => app(ServerConnectivityService::class)->getPlayers($this)
        );
    }

    public function getHasAcceptedEulaAttribute(): bool
    {
        return app(ServerConnectivityService::class)->getEulaAcceptedStatus($this);
    }

    /**
     * @throws NoStorageServiceConfiguredException
     */
    public function getStorageServiceAttribute(): ServerStorageServiceInterface
    {
        if ($this->ftp_username && $this->ftp_password && $this->ftp_host && $this->ftp_port) {
            return app(ServerFilesystemStorageService::class);
        }

        if ($this->ssh_username && $this->ssh_key && $this->ssh_port) {
            return app(ServerSshStorageService::class);
        }

        throw new NoStorageServiceConfiguredException($this->id);
    }

    // Relations

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * @codeCoverageIgnore Can't cover the boot (heh)
     */
    protected static function boot(): void
    {
        parent::boot();

        self::observe(ServerObserver::class);
    }
}
