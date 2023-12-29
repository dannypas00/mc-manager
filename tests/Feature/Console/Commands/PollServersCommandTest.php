<?php

namespace Tests\Feature\Console\Commands;

use App\Console\Commands\PollServersCommand;
use App\Jobs\PollServerJob;
use App\Models\Server;
use Artisan;
use Bus;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\FeatureTestCase;

#[CoversClass(PollServersCommand::class)]
class PollServersCommandTest extends FeatureTestCase
{
    public function test_that_job_is_dispatched(): void
    {
        Server::factory()->count(2)->create();
        Bus::fake(PollServerJob::class);

        Artisan::call(PollServersCommand::class);

        Bus::assertDispatchedTimes(PollServerJob::class, 2);
    }

    public function test_that_no_jobs_dispatched_without_servers(): void
    {
        Bus::fake(PollServerJob::class);

        Artisan::call(PollServersCommand::class);

        Bus::assertNotDispatched(PollServerJob::class);
    }
}
