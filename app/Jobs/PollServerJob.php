<?php

namespace App\Jobs;

use App\Enums\ServerStatus;
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

    private const DOWN_STATUSES = [
        ServerStatus::Down,
        ServerStatus::Unknown,
        ServerStatus::Error,
        ServerStatus::Stopping,
        ServerStatus::Starting
    ];

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
        $server->updateByPing();
        if (in_array($server->status, self::DOWN_STATUSES, true)) {
            $server->current_players = 0;
            $server->player_list = null;
            return;
        }
    }
}
