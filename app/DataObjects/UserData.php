<?php

namespace App\DataObjects;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

/**
 * @typescript
 */
#[MapOutputName(SnakeCaseMapper::class)]
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
