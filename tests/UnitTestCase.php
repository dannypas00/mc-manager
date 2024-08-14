<?php

namespace Tests;

use App\Models\User;
use Http;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use PHPUnit\Framework\Attributes\CoversNothing;
use Tests\Traits\MockAuthenticatedUserRepository;

#[CoversNothing]
abstract class UnitTestCase extends BaseTestCase
{
    use CreatesApplication;
    use MockAuthenticatedUserRepository;

    #[CoversNothing]
    public function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();

        $this->travelTo('09-12-2023');
        $this->freezeTime();
    }

    public function beUser(): User
    {
        $user = User::make(['name' => 'test', 'email' => 'test@test.com', 'password' => 'test']);
        $this->be($user);
        $this->mockAuthenticatedUserRepository($user);

        return $user;
    }
}
