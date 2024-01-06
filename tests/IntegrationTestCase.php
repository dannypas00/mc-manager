<?php

namespace Tests;

use File;
use Illuminate\Contracts\Process\ProcessResult;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversNothing;
use Process;
use Storage;
use Throwable;

#[CoversNothing]
class IntegrationTestCase extends UnitTestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;

    public const MINECRAFT_PATH = 'tests/sandbox/minecraft';
    public const MINECRAFT_BASE_PATH = 'tests/sandbox/minecraft_base';
    public const MINECRAFT_URL = 'https://piston-data.mojang.com/v1/objects/8dd1a28015f51b1803213892b50b7b4fc76e594d/server.jar';

    /**
     * @throws Throwable
     */
    public function createApplication(): Application
    {
        $application = parent::createApplication();

        // Set up minecraft base
        if (!File::exists(self::MINECRAFT_BASE_PATH . '/server.jar')) {
            $this->setupBase();
        }

        $this->startCompose();

        return $application;
    }

    /**
     * @throws Throwable
     */
    private function setupBase(): void
    {
        try {
            echo 'Setting up minecraft' . PHP_EOL;

            File::deleteDirectory(self::MINECRAFT_BASE_PATH);
            File::deleteDirectory(self::MINECRAFT_PATH);

            File::ensureDirectoryExists(self::MINECRAFT_BASE_PATH);
            File::ensureDirectoryExists(self::MINECRAFT_PATH);

            file_put_contents(self::MINECRAFT_BASE_PATH . '/server.jar', file_get_contents(self::MINECRAFT_URL));
            file_put_contents(self::MINECRAFT_BASE_PATH . '/eula.txt', 'eula=true');

            $this->startCompose('minecraft-base');
        } catch (Throwable $e) {
            File::deleteDirectory(self::MINECRAFT_BASE_PATH);
            File::deleteDirectory(self::MINECRAFT_PATH);
            throw $e;
        }
    }

    public function resetMinecraft(): void
    {
        $this->killCompose('minecraft');

        File::deleteDirectory(self::MINECRAFT_PATH);
        File::copyDirectory(self::MINECRAFT_BASE_PATH, self::MINECRAFT_PATH);

        $this->startCompose('minecraft');
    }

    public function stopCompose(): ProcessResult
    {
        return Process::run('docker compose -f docker-compose-test.yaml down')->throw();
    }

    public function killCompose(string $services): ProcessResult
    {
        return Process::run("docker compose -f docker-compose-test.yaml kill $services");
    }

    public function startCompose(string $services = ''): ProcessResult
    {
        return Process::run("docker compose -f docker-compose-test.yaml up -d $services")->throw();
    }
}
