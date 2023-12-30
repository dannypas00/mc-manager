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
            'ssh_key'         => '-----BEGIN OPENSSH PRIVATE KEY-----
b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAArAAAABNlY2RzYS
1zaGEyLW5pc3RwNTIxAAAACG5pc3RwNTIxAAAAhQQBdCb4l4oGSF5J5I+3pvI3zTPJyvWJ
5z+qdKLtZH7TR/Z+fzuXG3x2tH2A9tKhsZPxuBKPGrvpgzPArphunH+y7ucBjBwi4IF8pK
B82FwB9CPWWKWjikZF9nFtFt+gm4Mv32CjoqOmhPM1EHjavqt7zASx4VHCArZ40Aaua9Yg
OYgM0qIAAAEQ5EtpqORLaagAAAATZWNkc2Etc2hhMi1uaXN0cDUyMQAAAAhuaXN0cDUyMQ
AAAIUEAXQm+JeKBkheSeSPt6byN80zycr1iec/qnSi7WR+00f2fn87lxt8drR9gPbSobGT
8bgSjxq76YMzwK6Ybpx/su7nAYwcIuCBfKSgfNhcAfQj1lilo4pGRfZxbRbfoJuDL99go6
KjpoTzNRB42r6re8wEseFRwgK2eNAGrmvWIDmIDNKiAAAAQgEbg4CwdXC+584UjT5BF8c7
dgAfB/ZqOw2RSEx+AOGosli7YwoponJ4NiiEm4M2NRHqQYmsPq78UdiY03rKwWG8UAAAAB
Fyb290QDYwZTZmNTUyNGU5NQE=
-----END OPENSSH PRIVATE KEY-----
',
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
