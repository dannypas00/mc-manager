<?php

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Servers\ServerUpdateRequest;

class ServerUpdateController extends Controller
{
    /**
     * TODO: Type is immutable
     *
     * @return void
     */
    public function __invoke(int $id, ServerUpdateRequest $request)
    {
        // Set correct model values
        // (if ssh) test that ssh is accessible
        // Test that filesystem is accessible
        // Add server.properties file
    }
}
