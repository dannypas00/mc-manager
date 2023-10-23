<?php

namespace App\Models;

use App\Rcon\Rcon;
use Crypt;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
