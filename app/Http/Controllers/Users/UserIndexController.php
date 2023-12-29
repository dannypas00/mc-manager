<?php

declare(strict_types=1);

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\ModelSettings\UserSettings;
use App\Repositories\IndexRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use InvalidArgumentException;

class UserIndexController extends Controller
{
    /**
     * @throws InvalidArgumentException
     */
    public function __invoke(UserSettings $settings, IndexRepository $repository): LengthAwarePaginator
    {
        return $repository->getPaginator($settings);
    }
}
