<?php

namespace App\Jobs;

use App\Enums\ServerStatus;
use App\Models\Server;
use App\Repositories\Servers\ServerShowRepository;
use App\Repositories\Servers\ServerUpdateRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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

    public function handle(ServerShowRepository $showRepository, ServerUpdateRepository $updateRepository): void
    {
        $server = $showRepository->findOrFail($this->id);
        $this->updateServer($server, $updateRepository);
    }

    private function updateServer(Server $server, ServerUpdateRepository $updateRepository): void
    {
        $updateRepository->updateByPing($server);
        $server->refresh();

        if (in_array($server->status, self::DOWN_STATUSES, true)) {
            $server->current_players = 0;
        }
    }
}
