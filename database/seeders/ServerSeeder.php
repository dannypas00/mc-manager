<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Server;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\UniqueConstraintViolationException;

class ServerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            Server::factory()->create([
                'user_id'        => User::first()->id,
                'name'           => 'Test Server',
                'enabled'        => true,
                'minecraft_host' => 'minecraft',
                'minecraft_port' => '25565',
                'rcon_port'      => '25575',
                'rcon_password'  => 'test1234',
                'ftp_host'       => 'ftp',
                'ftp_port'       => '21',
                'ftp_username'   => 'mcm-test',
                'ftp_password'   => 'mcm-test',
                'ssh_host'       => 'ssh',
                'ssh_port'       => '22',
                'ssh_key'        => file_get_contents(base_path('.docker/local/private_key')),
            ]);
        } catch (UniqueConstraintViolationException) {
            // Server already exists
        }
    }
}
