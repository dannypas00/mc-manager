<?php

namespace Tests\Feature\Repositories\Servers;

use App\Models\Server;
use App\Models\User;
use App\Repositories\Servers\ServerShowRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\FeatureTestCase;

#[CoversClass(ServerShowRepository::class)]
class ServerShowRepositoryTest extends FeatureTestCase
{
    public function testShow(): void
    {
        $user = User::factory()->createOne();
        $server = Server::factory()->createOne();
        $server->users()->sync([$user->id]);

        $actual = app(ServerShowRepository::class)->findOrFail($server->id, ['users'], ['id', 'name']);

        $this->assertEquals($server->id, $actual->id);
        $this->assertEquals($user->id, $actual->users->first()->id);
        $this->assertNull($actual->local_ip);
    }
}
