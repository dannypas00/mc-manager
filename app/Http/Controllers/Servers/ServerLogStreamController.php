<?php

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;
use App\Repositories\Servers\FrontendServerShowRepository;
use Illuminate\Filesystem\FilesystemAdapter;
use Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ServerLogStreamController extends Controller
{
    private const LOG_NAME = 'logs/latest.log';

    /**
     * @codeCoverageIgnore Stream technically never ends unless user asynchronously breaks connection
     * Because of this, the test will run forever. The logic function however is properly tested
     */
    public function __invoke(int $id, FrontendServerShowRepository $showRepository): StreamedResponse
    {
        return response()
            ->stream(function () use ($showRepository, $id) {
                $ftp = $showRepository->show($id)->ftp;

                $lastSize = 0;

                while (sleep(0.1) === 0) {
                    if (connection_aborted() === 1) {
                        break;
                    }

                    $this->tryReadFile($ftp, $lastSize);
                }
            }, 200, [
                'X-Accel-Buffering' => 'no',
                'Cache-Control'     => 'no-cache',
                'Content-Type'      => 'text/event-stream'
            ]);
    }

    public function tryReadFile(FilesystemAdapter $ftp, int &$lastSize): void
    {
        $currentSize = $ftp->size(self::LOG_NAME);

        // If the filesize changed, sent a new message over the event stream
        if ($currentSize > $lastSize) {
            Log::debug('File size increased', ['lastSize' => $lastSize, 'currentSize' => $currentSize]);

            $stream = $ftp->readStream(self::LOG_NAME);

            $message = base64_encode(stream_get_contents($stream, null, $lastSize));
            $lastSize = $currentSize;

            // Send data
            echo 'event: ping' . PHP_EOL, "data: $message", PHP_EOL . PHP_EOL;

            // Push and end stream
            flush();
            clearstatcache();
            fclose($stream);
        }
    }
}
