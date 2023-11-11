<?php

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;
use Auth;
use Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ServerLogStreamController extends Controller
{
    public function __invoke(int $serverId): StreamedResponse
    {
        $stream = Auth::user()
            ?->servers()
            ->where('servers.id', $serverId)
            ->first()
            ?->ftp()
            ->readStream('logs/latest.log');


        if (!$stream) {
            abort(404, 'Server not found for user');
        }

        // https://pineco.de/simple-event-streaming-in-laravel/
        return response()->stream(function () use ($stream) {
            $lastSize = -1;
            $iteration = 0;

            while (true) {
                if (connection_aborted()) {
                    break;
                }

                $currentSize = fstat($stream)['size'];

                if ($currentSize > $lastSize) {
                    Log::debug('File size increased', ['lastSize' => $lastSize, 'currentSize' => $currentSize]);

                    $message = stream_get_contents($stream, false, $lastSize);
                    $lastSize = $currentSize;
                    $iteration++;

                    // Send data
                    echo "event: ping" . PHP_EOL, "data: $iteration" . PHP_EOL, "iteration: $iteration", PHP_EOL . PHP_EOL;
                    ob_flush();
                    flush();
                }

                sleep(5);
            }
        }, 200, [
            'Cache-Control' => 'no-cache',
            'Content-Type'  => 'text/event-stream',
        ]);
    }
}
