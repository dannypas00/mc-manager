<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Server>
 */
class ServerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'        => fn () => User::factory(),
            'name'           => $this->faker->name,
            'enabled'        => true,
            'minecraft_host' => $this->faker->ipv4(),
            'minecraft_port' => (int)$this->faker->bothify('2556#'),
            'rcon_port'      => (int)$this->faker->bothify('2557#'),
            'rcon_password'  => $this->faker->password(),
            'ftp_host'       => $this->faker->ipv4(),
            'ftp_port'       => 20,
            'ftp_username'   => $this->faker->userName(),
            'ftp_password'   => $this->faker->password(),
            'ssh_host'       => $this->faker->ipv4(),
            'ssh_port'       => 22,
            'ssh_key'        => $this->faker->text(),
        ];
    }
}
