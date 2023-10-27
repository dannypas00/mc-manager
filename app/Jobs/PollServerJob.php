<?php

namespace App\Jobs;

use App\Models\Server;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Str;

class PollServerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly int $id)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $server = $this->getServer();
        [$server->current_players, $server->maximum_players, $server->player_list] = $this->getUserCount($server);
        $server->save();
    }

    private function getServer(): Server
    {
        return Server::findOrFail($this->id);
    }

    private function getUserCount(Server $server): array
    {
        $server->rcon()->connect();
        $response = $server->rcon()->send_command('list');
        preg_match('/(\d+) of .* (\d+) .*: (.*)\x00\x00$/', $response, $matches);
        return [$matches[1], $matches[2], Str::replace(' ', ',', $matches[3])];
    }
}
