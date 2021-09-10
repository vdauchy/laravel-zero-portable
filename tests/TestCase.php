<?php

declare(strict_types=1);

namespace VDauchy\LaravelZeroPortable\Tests;

use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Application;
use LaravelZero\Framework\Testing\TestCase as BaseTestCase;
use NunoMaduro\LaravelConsoleSummary\SummaryCommand;
use VDauchy\LaravelZeroPortable\Configs\App;
use VDauchy\LaravelZeroPortable\Configs\Commands;
use VDauchy\LaravelZeroPortable\LaravelZeroPortable;

abstract class TestCase extends BaseTestCase
{
    protected $app;
    protected ?string $appNamespace;
    protected string $baseDir;
    protected array $commands;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->refreshApplicationWith(
            baseDir: data_dir(),
            appNamespace: app_namespace(),
            commands: []);

        parent::setUp();
    }

    /**
     * @return ApplicationContract
     * @throws \Exception
     */
    public function createApplication(): ApplicationContract
    {
        $app = LaravelZeroPortable::build(
            $this->baseDir,
            $this->appNamespace)
            ->config('app', fn() => new App(
                name: 'Test application',
                version: 'Test version',
                env: 'development',
                providers: []
            ))
            ->config('commands', fn() => new Commands(
                default: SummaryCommand::class,
                paths: [],
                add: $this->commands,
                hidden: [],
                remove: []
            ))
            ->bootstrap()
            ->getApplication();

        Application::setInstance($app);

        return $app;
    }

    /**
     * @param  string  $baseDir
     * @param  string|null  $appNamespace
     * @param  array  $commands
     */
    protected function refreshApplicationWith(string $baseDir, ?string $appNamespace = null, array $commands = [])
    {
        $this->baseDir = $baseDir;
        $this->appNamespace = $appNamespace;
        $this->commands = $commands;
        $this->refreshApplication();
    }
}
