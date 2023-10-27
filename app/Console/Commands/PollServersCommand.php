<?php

namespace App\Console\Commands;

use App\Models\Server;
use Illuminate\Console\Command;

class PollServersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'server:poll';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Poll server RCON to update database values';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Server::eachById(static function (int $id) {
            dispatch();
        });
    }
}
