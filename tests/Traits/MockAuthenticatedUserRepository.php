<?php

namespace Tests\Traits;

use App\Models\Server;
use App\Models\User;
use App\Repositories\Servers\ServerShowRepository;
use App\Repositories\Users\AuthenticatedUserRepository;
use Mockery\MockInterface;

trait MockAuthenticatedUserRepository
{
    public function mockAuthenticatedUserRepository(User $returns): void
    {
        $this->mock(
            AuthenticatedUserRepository::class,
            fn (MockInterface $mock) => $mock
                ->expects('getAuthenticatedUser')
                ->zeroOrMoreTimes()
                ->andReturn($returns)
        );
    }
}
