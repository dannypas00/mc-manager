<?php

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Servers\ServerTestSshCommandRequest;
use App\Models\Server;
use App\Services\ServerSshService;

class ServerTestSshCommandController extends Controller
{
    public function __invoke(ServerTestSshCommandRequest $request, ServerSshService $sshService): void
    {
        $mockServer = Server::make();
        $mockServer->local_ip = $request->get('host');
        $mockServer->ssh_username = $request->get('user');
        $mockServer->ssh_port = $request->get('port');
        $mockServer->ssh_key = $request->get('private_key') . PHP_EOL;

        $sshService->ping($mockServer);
    }
}
