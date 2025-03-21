<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ServerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Server extends Model
{
    /** @use HasFactory<ServerFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
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
}
