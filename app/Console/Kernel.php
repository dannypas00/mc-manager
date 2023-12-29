<?php

namespace App\Console;

use App\Console\Commands\PollServersCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // TODO: Replace with minecraft query or ping instead of rcon: https://wiki.vg/Query
        $schedule->command(PollServersCommand::class)
            ->everyFiveSeconds();
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
    }
}
