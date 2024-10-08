<?php

/** @noinspection ClassConstantCanBeUsedInspection */
declare(strict_types=1);

namespace App\Console\Commands\Typescript;

use File;
use FumeApp\ModelTyper\Actions\GenerateCliOutput;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionException;
use Str;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @codeCoverageIgnore This is only a dev command to generate typescript models for the developer, no need to test it since it doesn't run in production
 */
class ExportTypescriptModelsCommand extends Command
{
    // Don't replace this with the ::class constant, it breaks things when matching the parent class
    private const ABSTRACT_MODEL = 'Illuminate\Database\Eloquent\Model';

    private const EXPORT_NAMESPACE = 'App.Models';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'typescript:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export mysql models as typescript interfaces through the fumeapp/modeltyper package. This command assumes all models that need exporting extend the AbstractModel class';

    /**
     * Execute the console command.
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function handle(GenerateCliOutput $generateCliOutput): int
    {
        if (!app()->environment('local')) {
            $this->error('Command should only be run in local context.');

            return self::INVALID;
        }

        $body = $generateCliOutput($this->gatherModels(), false, true);

        $this->writeToFile($body);

        return self::SUCCESS;
    }

    /**
     * @return Collection of SplFileInfo objects, keyed by the model namespace
     */
    private function gatherModels(): Collection
    {
        return collect(File::files(app_path('Models')))
            ->mapWithKeys(
                fn (SplFileInfo $file, $two) => [
                    $this->guessModelNamespace($file->getPathname()) => $file
                ]
            )->filter(
                static function (SplFileInfo $file, string $namespace) {
                    return in_array(self::ABSTRACT_MODEL, class_parents($namespace), true)
                        && !(new ReflectionClass($namespace))->isAbstract();
                }
            )->sort();
    }

    /**
     * Take the filepath between app/Models and .php, replace forward slashes to backward slashes and we should have most of the PSR-4 namespace
     */
    private function guessModelNamespace(string $fileName): string
    {
        $baseName = Str::before(
            Str::replace(
                '/',
                '\\',
                Str::after($fileName, 'app/Models/')
            ),
            '.php'
        );

        return 'App\\Models\\' . $baseName;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function writeToFile(string $body): void
    {
        // Add two spaces (indent size for our typescript projects) to the start of each content line, ignoring empty lines
        $tabbedBody = preg_replace('/^(.+)/m', '  $1', $body);
        $namespace = self::EXPORT_NAMESPACE;

        // Disable eslint inspection and add all models to the App.Models namespace
        $content = "/* eslint-disable */\n\n// Generated by command, any edits will be overwritten\ndeclare namespace $namespace {\n$tabbedBody}\n";

        $filePath = config('typescript.output');

        File::put($filePath, $content);
    }
}
