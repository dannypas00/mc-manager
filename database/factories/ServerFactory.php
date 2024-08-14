<?php

namespace Database\Factories;

use App\Models\Server;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Server>
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
            'name'        => $this->faker->words(3, true),
            'description' => $this->faker->realTextBetween(20, 300),
            'public_ip'   => $this->faker->domainName(),
            'local_ip'    => $this->faker->ipv4(),
            'icon'        => 'dev-images/' . $this->faker->randomElement(['cavern', 'desert', 'plains']) . '-icon.png',
            'enabled'     => $this->faker->boolean(80),
            'rcon_password' => $this->faker->password,
            'current_players' => $this->faker->numberBetween(0, 25),
            'maximum_players' => $this->faker->numberBetween(25, 50),
        ];
    }

    public function withFtp(): static
    {
        return $this->state(
            fn (array $attributes) => [
                'enable_ftp' => true,
                'ftp_port'        => 21,
                'ftp_host'        => $this->faker->ipv4,
                'ftp_username'    => $this->faker->userName,
                'ftp_password'    => $this->faker->password,
            ]
        );
    }

    public function withSsh(): static
    {
        return $this->state(
            fn (array $attributes) => [
                'enable_ssh' => true,
                'ssh_username'    => 'mcm-test',
                'ssh_key'         => $this->faker->randomKey,
                'ssh_port'        => '22',
            ]
        );
    }
}
