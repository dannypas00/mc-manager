<?php

namespace App\DataObjects;

use App\Models\Server;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use DateTime;
use Spatie\LaravelData\Data;

/**
 * @typescript
 */
class UserData extends Data
{
    /**
     * @param array<ServerData>|null $servers
     */
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public ?string $profile_photo_url,
        #[WithCast(DateTimeInterfaceCast::class)]
        public ?Carbon $email_verified_at,
        #[WithCast(DateTimeInterfaceCast::class)]
        public ?Carbon $created_at,
        #[WithCast(DateTimeInterfaceCast::class)]
        public ?Carbon $updated_at,
        public ?array $servers,
    ) {
    }
}
