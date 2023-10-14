<?php

namespace Database\Seeders;

use App\Models\Server;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

         User::factory(10)->create();

         User::factory()->create([
             'name' => 'Test User',
             'email' => 'test@test.com',
             'password' => 'test1234'
         ]);

         Server::factory()->recycle(User::all())->count(50)->has(User::factory())->create();
    }
}
