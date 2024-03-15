<?php

namespace App\DataObjects;

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
        public string $email_verified_at,
    ) {
    }
}
