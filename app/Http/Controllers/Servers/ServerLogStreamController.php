<?php

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;
use Auth;
use Symfony\Component\HttpFoundation\Response;
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

        return response()->stream(
            function () use ($stream) {
                fpassthru($stream);
            },
            Response::HTTP_OK,
            [
                'Content-Type' => 'text/plain',
                'Content-Disposition' => 'attachment; filename="latest.log"',
            ]
        );
    }
}
