<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UserUpdateRequest;
use App\Models\User;
use App\Traits\PadsArrayWithNull;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserUpdateController extends Controller
{
    use PadsArrayWithNull;

    public function __invoke(int $id, UserUpdateRequest $request): JsonResponse
    {
        User::updateFrom($this->padArrayWithNull((new User)->getFillable(), $request->validated()));

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
