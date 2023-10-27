<?php

namespace App\Jobs;

use App\Enums\ServerStatus;
use App\Models\Server;
use Http;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\ConnectionException;
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
        $this->updateServer($server);
        $server->save();
    }

    private function getServer(): Server
    {
        return Server::findOrFail($this->id);
    }

    private function updateServer(Server $server): void
    {
        $server->status = $this->getServerStatus($server);
        [$server->current_players, $server->maximum_players, $server->player_list] = $this->getUserCount($server);
    }

    private function getServerStatus(Server $server): ServerStatus
    {
        // Try requesting game port first
        try {
            if (!Http::head($server->local_ip . ':' . $server->rcon_port)->successful()) {
                return ServerStatus::Down;
            }
        } catch (ConnectionException) {
            // Do nothing on empty response
        }
        if (!$server->rcon()) {
            return ServerStatus::Starting;
        }
        if ($server->rcon()->send_command('ping')) {
            return ServerStatus::Up;
        }
        return ServerStatus::Unknown;
    }

    private function getUserCount(Server $server): array
    {
        $response = $server->rcon()->send_command('list');
        preg_match('/(\d+) of .* (\d+) .*: (.*)\x00\x00$/', $response, $matches);
        return [$matches[1], $matches[2], Str::replace(' ', ',', $matches[3])];
    }
}
