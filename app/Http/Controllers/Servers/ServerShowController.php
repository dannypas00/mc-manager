<?php

declare(strict_types=1);

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\ModelSettings\ServerSettings;
use App\Repositories\ShowRepository;
use Illuminate\Database\Eloquent\Model;

class ServerShowController extends Controller
{
    public function __invoke(int $id, ServerSettings $settings, ShowRepository $repository): Server|Model
    {
        return $repository->show($settings, $id);
    }
}
