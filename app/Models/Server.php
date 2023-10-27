<?php

namespace App\Models;

use App\Rcon\Rcon;
use Crypt;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $current_players
 * @property int $maximum_players
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\ServerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Server newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Server newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Server query()
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereCurrentPlayers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereLocalIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereMaximumPlayers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server wherePublicIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereRconPassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereRconPort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereUpdatedAt($value)
 * @property string|null $player_list Comma-separated list of users
 * @method static \Illuminate\Database\Eloquent\Builder|Server wherePlayerList($value)
 * @mixin \Eloquent
 */
class Server extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'local_ip',
        'public_ip',
        'port',
        'rcon_port',
        'enabled',
        'rcon_password',
        'current_players',
        'maximum_players',
    ];

    public function rconPassword(): Attribute
    {
        return Attribute::make(
            get: static fn (string $value): string => Crypt::decrypt($value),
            set: static fn (string $value): string => Crypt::encrypt($value),
        );
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function rcon(): Rcon
    {
        $rcon = new Rcon($this->local_ip, $this->rcon_port, $this->rcon_password, 30);
        $rcon->connect();
        return $rcon;
    }
}
