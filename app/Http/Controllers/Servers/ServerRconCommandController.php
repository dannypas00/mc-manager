<?php

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Servers\ServerRconCommandRequest;
use App\Repositories\Servers\FrontendServerShowRepository;
use Auth;
use Illuminate\Http\JsonResponse;
use Str;

/**
 * @codeCoverageIgnore Mocking the RCON class seems like an impossible task after an hour of trying
 */
class ServerRconCommandController extends Controller
{
    private const LOG_NAME = 'logs/latest.log';

    public function __invoke(int $id, ServerRconCommandRequest $request, FrontendServerShowRepository $showRepository): JsonResponse
    {
        $server = $showRepository->show($id);

        $command = $request->get('command');
        $log = $this->getCommandToLog($command) . PHP_EOL;

        $response = $server->rcon->send_command($command);
        $response = $this->handleNewlines($command, $response);
        $log .= $this->getResponseToLog($response);

        $server->storage_service->append($server, self::LOG_NAME, $log);

        return new JsonResponse($response);
    }

    private function getCommandToLog(string $command): string
    {
        $timestamp = '[' . now()->format('H:m:s') . ']';
        $thread = '[' . Auth::user()->name . '/INFO]';

        return "$timestamp $thread: $command" . PHP_EOL;
    }

    private function getResponseToLog(string $response): string
    {
        $timestamp = '[' . now()->format('H:m:s') . ']';
        $thread = '[Command Response/INFO]';

        $responseEntry = '';
        foreach (explode(PHP_EOL, $response) as $entry) {
            $responseEntry .= "$timestamp $thread: $entry" . PHP_EOL;
        }

        return Str::replace("\x00", '', $responseEntry);
    }

    private function handleNewlines(string $command, string $response): string
    {
        // Add newline before each / from help
        if (Str::startsWith($command, 'help')) {
            return Str::replace('/', PHP_EOL . '/', $response);
        }

        // Add newline before command in error
        if (Str::startsWith($response, 'Unknown or incomplete command, see below for error')) {
            return Str::replaceFirst('error', 'error' . PHP_EOL, $response);
        }

        return $response;
    }
}
