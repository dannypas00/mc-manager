<?php

namespace Tests\Feature\Http\Controllers\Servers;

use App\Http\Controllers\Servers\ServerLogStreamController;
use App\Models\Server;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\FeatureTestCase;
use Tests\Traits\MockServerFilesystemStorageService;

#[CoversClass(ServerLogStreamController::class)]
class ServerLogStreamControllerTest extends FeatureTestCase
{
    use MockServerFilesystemStorageService;

    public function test_it_reads_file_correctly(): void
    {
        $this->mockFsSize('logs/latest.log', 10);
        $this->mockFsTail('logs/latest.log', 'teststring');

        $lastSize = 0;
        // Have to start an output buffer that belongs purely to the test, otherwise phpunit will lose its own output buffer when we clean it
        ob_start();
        (new ServerLogStreamController())->tryReadFile(
            Server::make(),
            $this->getFsMock(),
            $lastSize
        );
        $output = ob_get_clean();

        $this->assertEquals($output, "event: ping\ndata: dGVzdHN0cmluZw==\n\n");
    }
}
