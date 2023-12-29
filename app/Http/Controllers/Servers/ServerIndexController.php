<?php

declare(strict_types=1);

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;
use App\ModelSettings\ServerSettings;
use App\Repositories\IndexRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ServerIndexController extends Controller
{
    public function __invoke(ServerSettings $settings, IndexRepository $repository): LengthAwarePaginator
    {
        return $repository->getPaginator($settings);
    }
}
