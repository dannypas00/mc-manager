<?php

namespace App\Models;

use App\Enums\ServerStatus;
use App\Rcon\Rcon;
use Crypt;
use Database\Factories\ServerFactory;
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
use Storage;

/**
 * App\Models\Server
 *
 * @property int $id
 * @property int $enabled
 * @property int $port
 * @property int $rcon_port
 * @property string $name
 * @property string|null $description
 * @property string $icon
 * @property string|null $local_ip
 * @property string $public_ip
 * @property string $rcon_password
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $current_players
 * @property int $maximum_players
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
 * @method static ServerFactory factory($count = null, $state = [])
 * @method static Builder|Server newModelQuery()
 * @method static Builder|Server newQuery()
 * @method static Builder|Server query()
 * @method static Builder|Server whereCreatedAt($value)
 * @method static Builder|Server whereCurrentPlayers($value)
 * @method static Builder|Server whereDescription($value)
 * @method static Builder|Server whereEnabled($value)
 * @method static Builder|Server whereIcon($value)
 * @method static Builder|Server whereId($value)
 * @method static Builder|Server whereLocalIp($value)
 * @method static Builder|Server whereMaximumPlayers($value)
 * @method static Builder|Server whereName($value)
 * @method static Builder|Server wherePort($value)
 * @method static Builder|Server wherePublicIp($value)
 * @method static Builder|Server whereRconPassword($value)
 * @method static Builder|Server whereRconPort($value)
 * @method static Builder|Server whereUpdatedAt($value)
 * @property string|null $player_list Comma-separated list of users
 * @method static Builder|Server wherePlayerList($value)
 * @property ServerStatus $status
 * @method static Builder|Server whereStatus($value)
 * @property int $enable_ftp
 * @property int $is_sftp
 * @property int $use_ssh_auth
 * @property int|null $ftp_port
 * @property string|null $ftp_host
 * @property string|null $ftp_username Contains private key when using ssh key auth
 * @property string|null $ftp_password Contains pass phrase when using ssh key auth
 * @method static Builder|Server whereEnableFtp($value)
 * @method static Builder|Server whereFtpHost($value)
 * @method static Builder|Server whereFtpPassword($value)
 * @method static Builder|Server whereFtpPort($value)
 * @method static Builder|Server whereFtpUsername($value)
 * @method static Builder|Server whereIsSftp($value)
 * @method static Builder|Server whereUseSshAuth($value)
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
}
