<?php

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Servers\ServerLogStreamRequest;
use App\Models\Server;
use App\Repositories\Servers\FrontendServerShowRepository;
use App\Services\ServerStorageServiceInterface;
use Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ServerLogStreamController extends Controller
{
    private const LOG_NAME = 'logs/latest.log';

    /**
     * @codeCoverageIgnore Stream technically never ends unless user asynchronously breaks connection
     * Because of this, the test will run forever. The logic function however is properly tested
     */
    public function __invoke(int $id, ServerLogStreamRequest $request, FrontendServerShowRepository $showRepository): StreamedResponse
    {
        return response()
            ->stream(function () use ($request, $showRepository, $id) {
                // TODO: Refactor to allow ssh streaming of java process output
                $server = $showRepository->show($id);
                $storage = $server->storage_service;

                $lastSize = $request->get('start', 0);

                for ($i = 0; $i < 120; $i++) {
                    sleep(0.5);
                    if (connection_aborted() === 1) {
                        break;
                    }

                    $this->tryReadFile($server, $storage, $lastSize);
                }

                $size = $storage->size($server, self::LOG_NAME);
                echo 'event: end' . PHP_EOL . "data: $size" . PHP_EOL . PHP_EOL;
            }, 200, [
                'X-Accel-Buffering' => 'no',
                'Cache-Control'     => 'no-cache',
                'Content-Type'      => 'text/event-stream'
            ]);
    }

    public function tryReadFile(Server $server, ServerStorageServiceInterface $storage, int &$lastSize): void
    {
        $currentSize = $storage->size($server, self::LOG_NAME);

        // If the filesize changed, sent a new message over the event stream
        if ($currentSize > $lastSize) {
            Log::debug('File size increased', ['lastSize' => $lastSize, 'currentSize' => $currentSize]);

            $message = base64_encode($storage->tail($server, self::LOG_NAME, $lastSize));
            $lastSize = $currentSize;

            // Send data
            echo 'event: ping' . PHP_EOL, "data: $message", PHP_EOL . PHP_EOL;

            // Push and end stream
            flush();
            clearstatcache();
        }
    }
}
