<?php

declare(strict_types=1);

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\ModelSettings\UserSettings;
use App\Repositories\ShowRepository;
use Illuminate\Database\Eloquent\Model;

class UserShowController extends Controller
{
    /**
     * @codeCoverageIgnore It's just a show
     */
    public function __invoke(int $id, UserSettings $settings, ShowRepository $repository): User|Model
    {
        return $repository->show($settings, $id);
    }
}
