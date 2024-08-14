<?php

namespace Database\Seeders;

use App\Models\Server;
use App\Models\User;
use Exception;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @throws Exception
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        /**
         * @var User $testUser
         */
        $testUser = User::factory()->create([
            'name'     => 'Test User',
            'email'    => 'test@test.com',
            'password' => 'test1234'
        ]);

        $testUser->servers()->create([
            'name'            => 'test_server',
            'description'     => 'Local docker test server',
            'icon'            => 'dev-images/cavern-icon.png',
            'local_ip'        => '172.17.0.1',
            'public_ip'       => 'mcm-minecraft',
            'port'            => 25565,
            'rcon_port'       => 25575,
            'enabled'         => true,
            'rcon_password'   => 'test1234',
            'current_players' => 0,
            'maximum_players' => 20,
            'enable_ftp'      => true,
            'is_sftp'         => false,
            'use_ssh_auth'    => false,
            'ftp_port'        => 21,
            'ftp_host'        => 'mcm-ftp',
            'ftp_username'    => 'mcm-test',
            'ftp_password'    => 'mcm-test',
            'enable_ssh'      => true,
            'ssh_username'    => 'mcm-test',
            'ssh_key'         => file_get_contents(base_path('.docker/local/private_key')),
            'ssh_port'        => '2222',
        ]);

        if (env('SEED_RANDOM_SERVERS', false)) {
            User::factory(10)
                ->create()
                ->each(static function (User $user) {
                    $user->servers()->syncWithoutDetaching(
                        Server::factory()
                            ->count(random_int(2, 7))
                            ->create()
                            ->pluck('id')
                    );
                });
        }
    }
}
