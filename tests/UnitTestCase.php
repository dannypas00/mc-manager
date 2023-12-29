<?php

namespace Tests;

use App\Models\User;
use Http;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use PHPUnit\Framework\Attributes\CoversNothing;

#[CoversNothing]
abstract class UnitTestCase extends BaseTestCase
{
    use CreatesApplication;

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

        return $user;
    }
}
