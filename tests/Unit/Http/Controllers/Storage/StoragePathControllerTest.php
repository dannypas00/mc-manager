<?php

namespace Tests\Unit\Http\Controllers\Storage;

use App\Http\Controllers\Storage\StoragePathController;
use Inertia\Testing\AssertableInertia;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Traits\MocksFrontendServerShowRepository;
use Tests\Traits\MocksServerStorageService;
use Tests\UnitTestCase;

#[CoversClass(StoragePathController::class)]
class StoragePathControllerTest extends UnitTestCase
{
    use MocksFrontendServerShowRepository;
    use MocksServerStorageService;

    public function testReturnsInertiaResponse(): void
    {
        $this->beUser();
        $this->mockFrontendServerShow();
        $this->mockServerStorageServiceListContents(['files' => ['testpath']]);

        $response = $this->get(route('servers.files', ['id' => 1, 'path' => 'test']));

        /** @noinspection StaticInvocationViaThisInspection */
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Servers/Files/ServerFiles')
            ->where('path', 'test')
            ->where('files', ['testpath'])
        );
    }

    public function testItShouldEnforceAuthentication(): void
    {
        $this->get(
            route('servers.files', ['id' => 1, 'path' => 'testpath'])
        )->assertRedirect(route('auth.login'));
    }
}
