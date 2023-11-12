<?php

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Filesystem\FilesystemAdapter;
use Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ServerLogStreamController extends Controller
{
    private const LOG_NAME = 'logs/latest.log';

    public function __invoke(int $serverId): StreamedResponse
    {
        // https://pineco.de/simple-event-streaming-in-laravel/
        return response()->stream(function () use ($serverId) {
            $ftp = $this->getFtp($serverId);

            $lastSize = 0;

            while (true) {
                if (connection_aborted() === 1) {
                    break;
                }

                $currentSize = $ftp->size(self::LOG_NAME);

                if ($currentSize > $lastSize) {
                    Log::debug('File size increased', ['lastSize' => $lastSize, 'currentSize' => $currentSize]);

                    $stream = $ftp->readStream(self::LOG_NAME);

                    $message = base64_encode(stream_get_contents($stream, null, $lastSize));
                    $lastSize = $currentSize;

                    // Send data
                    echo "event: ping" . PHP_EOL, "data: $message", PHP_EOL . PHP_EOL;

                    // Push and end stream
                    flush();
                    clearstatcache();
                    fclose($stream);
                } else {
                    Log::debug('No new info');
                }

                sleep(0.1);
            }
        },
            200,
            [
                'X-Accel-Buffering' => 'no',
                'Cache-Control'     => 'no-cache',
                'Content-Type'      => 'text/event-stream',
            ]
        );
    }

    /**
     * @param int $serverId
     * @return FilesystemAdapter
     */
    public function getFtp(int $serverId): FilesystemAdapter
    {
        return Auth::user()
            ?->servers()
            ->where('servers.id', $serverId)
            ->first()
            ?->ftp();
    }
}
