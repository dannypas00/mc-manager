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
            'local_ip'        => 'mcm-minecraft',
            'public_ip'       => 'mcm-minecraft',
            'port'            => 25565,
            'rcon_port'       => 25575,
            'enabled'         => true,
            'rcon_password'   => 'test1234',
            'current_players' => 0,
            'maximum_players' => 20,
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
