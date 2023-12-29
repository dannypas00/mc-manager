<?php

namespace Tests\Feature\Repositories\Servers;

use App\Models\Server;
use App\Repositories\Servers\FrontendServerShowRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\FeatureTestCase;

#[CoversClass(FrontendServerShowRepository::class)]
class FrontendServerShowRepositoryTest extends FeatureTestCase
{
    public function testShowAuthenticated(): void
    {
        $user = $this->beUser();
        $user->save();
        $server = Server::factory()->createOne();
        $user->servers()->sync([$server->id]);

        $result = app(FrontendServerShowRepository::class)->show($server->id);

        $this->assertEquals($server->id, $result->id);
    }

    public function testShowUnauthenticated(): void
    {
        $this->beUser()->save();
        $server = Server::factory()->createOne();

        $this->expectException(HttpException::class);
        app(FrontendServerShowRepository::class)->show($server->id);
    }
}
