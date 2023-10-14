<?php

namespace App\Models;

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
    ];

    public function rconPassword(): Attribute
    {
        return Attribute::make(
            get: static fn ($value) => Crypt::decrypt($value),
            set: static fn ($value) => Crypt::encrypt($value),
        );
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
