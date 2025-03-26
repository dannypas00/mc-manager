<?php

declare(strict_types=1);

namespace App\Models;

use App\Rcon\Rcon;
use Database\Factories\ServerFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Carbon;
use Storage;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property bool $enabled
 * @property string|null $minecraft_host
 * @property int|null $minecraft_port
 * @property int|null $rcon_port
 * @property mixed|null $rcon_password
 * @property string|null $ftp_host
 * @property int|null $ftp_port
 * @property string|null $ftp_username
 * @property mixed|null $ftp_password
 * @property string|null $ssh_host
 * @property int|null $ssh_port
 * @property mixed|null $ssh_key
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Rcon $rcon
 * @property-read User $user
 *
 * @method static ServerFactory factory($count = null, $state = [])
 * @method static Builder<static>|Server newModelQuery()
 * @method static Builder<static>|Server newQuery()
 * @method static Builder<static>|Server query()
 * @method static Builder<static>|Server whereCreatedAt($value)
 * @method static Builder<static>|Server whereEnabled($value)
 * @method static Builder<static>|Server whereFtpHost($value)
 * @method static Builder<static>|Server whereFtpPassword($value)
 * @method static Builder<static>|Server whereFtpPort($value)
 * @method static Builder<static>|Server whereFtpUsername($value)
 * @method static Builder<static>|Server whereId($value)
 * @method static Builder<static>|Server whereMinecraftHost($value)
 * @method static Builder<static>|Server whereMinecraftPort($value)
 * @method static Builder<static>|Server whereName($value)
 * @method static Builder<static>|Server whereRconPassword($value)
 * @method static Builder<static>|Server whereRconPort($value)
 * @method static Builder<static>|Server whereSshHost($value)
 * @method static Builder<static>|Server whereSshKey($value)
 * @method static Builder<static>|Server whereSshPort($value)
 * @method static Builder<static>|Server whereUpdatedAt($value)
 * @method static Builder<static>|Server whereUserId($value)
 *
 * @property-read mixed $filesystem
 *
 * @mixin Eloquent
 * @mixin IdeHelperServer
 */
class Server extends Model
{
    /** @use HasFactory<ServerFactory> */
    use HasFactory;

    private Rcon $rcon;

    protected $fillable = [
        'name',
        'user_id',
        'enabled',
        // Minecraft
        'minecraft_host',
        'minecraft_port',
        'rcon_port',
        'rcon_password',
        // FTP
        'ftp_host',
        'ftp_port',
        'ftp_username',
        'ftp_password',
        // SSH
        'ssh_host',
        'ssh_port',
        'ssh_key',
    ];

    protected $hidden = [
        'rcon_port',
        'rcon_password',
        'ftp_host',
        'ftp_port',
        'ftp_username',
        'ftp_password',
        'ssh_host',
        'ssh_port',
        'ssh_key',
    ];

    protected function casts(): array
    {
        return [
            'enabled'       => 'boolean',
            'rcon_password' => 'encrypted',
            'ftp_password'  => 'encrypted',
            'ssh_key'       => 'encrypted',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return Attribute<Rcon>
     */
    public function rcon(): Attribute
    {
        return Attribute::get(fn () => new Rcon(
            $this->minecraft_host,
            $this->rcon_port,
            $this->rcon_password,
            30,
        ))->shouldCache();
    }

    /**
     * @return Attribute<FilesystemAdapter>
     */
    public function filesystem(): Attribute
    {
        return Attribute::get(
            fn () => Storage::createFtpDriver(
                [
                    'host'     => $this->ftp_host,
                    'port'     => $this->ftp_port,
                    'username' => $this->ftp_username,
                    'password' => $this->ftp_password,
                ]
            )
        );
    }
}
