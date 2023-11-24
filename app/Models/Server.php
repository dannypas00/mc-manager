<?php

namespace App\Models;

use App\Enums\ServerStatus;
use App\Rcon\Rcon;
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
use Log;
use Storage;
use xPaw\MinecraftPing;
use xPaw\MinecraftPingException;
use xPaw\MinecraftQuery;
use xPaw\MinecraftQueryException;

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
 * @property int $current_players
 * @property int $maximum_players
 * @property string|null $player_list Comma-separated list of users
 * @property string $name
 * @property string|null $description
 * @property string|null $version
 * @property string $icon
 * @property string|null $local_ip
 * @property string $public_ip
 * @property string $rcon_password
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
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
 * @method static Builder|Server wherePlayerList($value)
 * @method static Builder|Server wherePort($value)
 * @method static Builder|Server wherePublicIp($value)
 * @method static Builder|Server whereRconPassword($value)
 * @method static Builder|Server whereRconPort($value)
 * @method static Builder|Server whereStatus($value)
 * @method static Builder|Server whereUpdatedAt($value)
 * @method static Builder|Server whereUseSshAuth($value)
 * @method static Builder|Server whereVersion($value)
 * @mixin Eloquent
 */
class Server extends Model
{
    use HasFactory;

    private ?Rcon $rcon = null;
    private FilesystemAdapter|null $fileStorageDisk = null;

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

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function rcon(): Rcon|false
    {
        if (!$this->rcon) {
            $this->rcon = new Rcon($this->local_ip, $this->rcon_port, $this->rcon_password, 30);
            if (!$this->rcon->connect()) {
                return false;
            }
        }

        return $this->rcon;
    }

    public function ftp(): FtpAdapter|FilesystemAdapter
    {
        if (!$this->fileStorageDisk) {
            $config = [
                'driver' => $this->is_sftp ? 'sftp' : 'ftp',
                'host'   => $this->ftp_host,
                'port'   => $this->ftp_port,

                ($this->use_ssh_auth ? 'privateKey' : 'username') => $this->ftp_username,
                ($this->use_ssh_auth ? 'passphrase' : 'password') => $this->ftp_password,
            ];

            $this->fileStorageDisk = $this->is_sftp
                ? Storage::createSftpDriver($config)
                : Storage::createFtpDriver($config);
        }

        return $this->fileStorageDisk;
    }

    public function updateByPing(bool $save = true): void
    {
        $data = $this->ping(false);

        if ($data) {
            $this->description = $data['description']['text'];
            $this->current_players = $data['players']['online'];
            $this->maximum_players = $data['players']['max'];
            $this->version = $data['version']['name'];

            if ($save) {
                $this->save();
            }
        }
    }

    public function ping(bool $save = true): mixed
    {
        $ping = (new MinecraftPing($this->local_ip, $this->port));

        try {
            $response = $ping->Query();

            if (!$response) {
                $this->status = ServerStatus::Starting;
            } elseif (is_array($response)) {
                $this->status = ServerStatus::Up;
            } else {
                $this->status = ServerStatus::Unknown;
            }
        } catch (MinecraftPingException $e) {
            $ping->close();
            $this->status = ServerStatus::Down;
            $this->save();
        } finally {
            $ping->close();

            if ($save) {
                $this->save();
            }

            return $response ?? false;
        }
    }

    public function getPlayerListAttribute(): array
    {
        return Cache::remember($this->id . '-server-plaayer-list', 60, function (): array {
            try {
                $query = new MinecraftQuery();
                $query->Connect($this->local_ip, $this->port);
                $list = $query->GetPlayers();

                if ($list) {
                    return $list;
                }
                return [];
            } catch (MinecraftQueryException $e) {
                Log::error('Exception getting player list', ['exception' => $e::class, 'message' => $e->getMessage(), 'trace' => $e->getTrace()]);
                return [];
            }
        });
    }
}
