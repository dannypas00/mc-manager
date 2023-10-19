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

        User::factory(10)->create();

        User::factory()->create([
            'name'     => 'Test User',
            'email'    => 'test@test.com',
            'password' => 'test1234'
        ]);

        User::all()->each(static function (User $user) {
            $user->servers()->sync(
                Server::factory()
                    ->count(random_int(2, 7))
                    ->create()
                    ->pluck('id')
            );
        });
    }
}
