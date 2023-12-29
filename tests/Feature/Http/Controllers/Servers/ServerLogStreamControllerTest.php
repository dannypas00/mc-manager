<?php

namespace Tests\Feature\Http\Controllers\Servers;

use App\Http\Controllers\Servers\ServerLogStreamController;
use PHPUnit\Framework\Attributes\CoversClass;
use Storage;
use Tests\FeatureTestCase;

#[CoversClass(ServerLogStreamController::class)]
class ServerLogStreamControllerTest extends FeatureTestCase
{
    public function test_it_reads_file_correctly(): void
    {
        $disk = Storage::fake();
        $disk->put('logs/latest.log', 'teststring');

        $lastSize = 0;
        // Have to start an output buffer that belongs purely to the test, otherwise phpunit will lose its own output buffer when we clean it
        ob_start();
        (new ServerLogStreamController())->tryReadFile($disk, $lastSize);
        $output = ob_get_clean();

        $this->assertEquals($output, "event: ping\ndata: dGVzdHN0cmluZw==\n\n");
    }
}
