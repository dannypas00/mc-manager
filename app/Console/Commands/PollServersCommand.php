<?php

namespace App\Console\Commands;

use App\Jobs\PollServerJob;
use App\Models\Server;
use Illuminate\Console\Command;

class PollServersCommand extends Command
{
    protected $signature = 'server:poll';

    protected $description = 'Poll server RCON to update database values';

    public function handle(): void
    {
        Server::lazy()->pluck('id')->each(static function (int $id) {
            dispatch(new PollServerJob($id));
        });
    }
}
