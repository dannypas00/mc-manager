<?php

namespace Tests\Feature\Models;

use App\Models\Server;
use App\Models\User;
use PHPUnit\Framework\Attributes\CoversClass;
use Storage;
use Tests\FeatureTestCase;

#[CoversClass(User::class)]
class UserTest extends FeatureTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        User::flushEventListeners();
    }

    public function testPassword(): void
    {
        $user = User::factory()->createOne();

        $user->password = 'test1234';
        $user->save();
        $user->refresh();

        // Assert password is not stored in plain-text
        $this->assertDatabaseMissing('users', ['password' => 'test1234']);

        // Assert password does not return plain
        $this->assertNotEquals('test1234', $user->password);
    }

    public function testIcon(): void
    {
        $disk = Storage::fake('profile-images');
        $disk->put('test.jpg', 'test');
        $expected = $disk->url('test.jpg');

        $user = User::factory(['icon' => 'test.jpg'])->createOne();
        $this->assertEquals($expected, $user->icon);
    }

    public function testServers(): void
    {
        $user = User::factory()->createOne();

        // Assert user doesn't have servers yet
        $this->assertDatabaseMissing('server_user', ['user_id' => $user->id]);

        // Attach users
        $servers = Server::factory()->count(5)->create();
        $user->servers()->sync($servers);

        // Assert server has users now
        $this->assertDatabaseHas('server_user', ['user_id' => $user->id]);
        $user->loadMissing(['servers']);
        $this->assertCount(5, $user->servers);

        // Remove users
        $user->servers()->delete();
        $this->assertDatabaseMissing('server_user', ['user_id' => $user->id]);
        $user->refresh();
        $this->assertEmpty($user->servers);
    }
}
