<?php

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Servers\ServerTestFtpRequest;
use App\Http\Requests\Servers\ServerTestSshCommandRequest;
use App\Models\Server;
use App\Services\ServerConnectivityService;
use App\Services\ServerFilesystemStorageService;
use App\Services\ServerSshStorageService;
use League\Flysystem\FilesystemException;

class ServerTestFtpController extends Controller
{
    /**
     * @throws FilesystemException
     */
    public function __invoke(ServerTestFtpRequest $request, ServerFilesystemStorageService $storageService): void
    {
        $mockServer = Server::make();
        $mockServer->ftp_host = $request->get('host');
        $mockServer->ftp_username = $request->get('user');
        $mockServer->ftp_port = $request->get('port');
        $mockServer->ftp_password = $request->get('password');
        $mockServer->is_sftp = $request->get('is_sftp');

        $storageService->getFtp($mockServer)->directoryExists('/');
    }
}
