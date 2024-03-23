<?php

namespace App\DataObjects;

use App\Models\User;
use Carbon\Carbon;
use Spatie\LaravelData\Data;

/**
 * @typescript
 */
class UserData extends Data
{
    public function __construct(
        public string $name,
        public string $email,
        public string $profile_photo_url,
        public ?Carbon $email_verified_at,
    ) {
    }

    public static function fromModel(User $model): UserData
    {
        return new self(
            $model->name,
            $model->email,
            $model->profile_photo_url,
            $model->email_verified_at,
        );
    }
}
