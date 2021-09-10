<?php

declare(strict_types=1);

namespace VDauchy\LaravelZeroPortable;

use Closure;
use Exception;
use Illuminate\Console\Scheduling\ScheduleRunCommand;
use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use LaravelZero\Framework\Bootstrap\BaseLoadConfiguration;
use NunoMaduro\Collision\Adapters\Laravel\Commands\TestCommand;
use NunoMaduro\LaravelConsoleSummary\SummaryCommand;
use Symfony\Component\Console\Command\HelpCommand;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use VDauchy\LaravelZeroPortable\Configs\App;
use VDauchy\LaravelZeroPortable\Configs\Commands;
use VDauchy\LaravelZeroPortable\Kernel as ZeroKernel;

class LaravelZeroPortable implements LaravelZeroPortableInterface
{
    private Application $app;
    private array $configurations = [];

    public static function build(string $basePath, ?string $appNamespace = null): static
    {
        if (! defined('LARAVEL_START')) {
            define('LARAVEL_START', microtime(true));
        }

        if (is_null($appNamespace) && file_exists($composerPath = "$basePath/composer.json")) {
            $namespaces = Arr::get(json_decode(file_get_contents($composerPath), true), 'autoload.psr-4', []);
            $appNamespace = trim(array_keys($namespaces)[0] ?? '', '\\');
        }

        if (empty($appNamespace)) {
            throw new Exception("A Psr-4 namespace for your project must be given.");
        }

        return new static($basePath, $appNamespace);
    }

    /**
     * @param  string  $basePath
     * @param  array  $commands
     * @param  array  $serviceProviders
     * @return static
     */
    public static function buildDefault(string $basePath, array $commands = [], array $serviceProviders = []): static
    {
        return static::build($basePath)
            ->config('app', fn () => new App(
                // phpcs:ignore
                name: basename($_SERVER['PHP_SELF']),
                version: app('git.version'),
                env: 'development',
                providers: $serviceProviders,
            ))
            ->config('commands', fn () => new Commands(
                default: SummaryCommand::class,
                paths: [],
                add: $commands,
                hidden: [SummaryCommand::class, HelpCommand::class, ScheduleRunCommand::class,],
                remove: [TestCommand::class,],
            ));
    }

    /**
     * @param  string  $basePath
     * @param  string  $appNamespace
     */
    protected function __construct(string $basePath, string $appNamespace)
    {
        $this->app = Application::withNamespace($basePath, $appNamespace);

        $this->app->singleton(ConsoleKernel::class, ZeroKernel::class);
        $this->app->singleton(ExceptionHandler::class, Handler::class);

        $this->app->bind(BaseLoadConfiguration::class, PortableLoadConfiguration::class);

        $this->app->instance(LaravelZeroPortableInterface::class, $this);
    }

    /**
     * @return $this
     */
    public function init(Closure $init): static
    {
        $init($this->app);

        return $this;
    }

    /**
     * @param  string  $key
     * @param  Closure  $content
     * @return LaravelZeroPortable
     */
    public function config(string $key, Closure $content): static
    {
        $this->configurations[$key] = fn(?Closure $parent = null) => $content($this->configurations[$key] ?? null);

        return $this;
    }

    /**
     * @param  ArgvInput|null  $input
     * @param  ConsoleOutput|null  $output
     * @return int
     */
    public function run(?ArgvInput $input = null, ?ConsoleOutput $output = null): int
    {
        return tap(($kernel = $this->getKernel())->handle(
            $input ??= new ArgvInput(),
            $output ?? new ConsoleOutput(),
        ), fn (int $status) => $kernel->terminate($input, $status));
    }

    /**
     * @return $this
     */
    public function bootstrap(): static
    {
        $this->getKernel()->bootstrap();

        return $this;
    }

    /**
     * @return Collection
     */
    public function getConfigurations(): Collection
    {
        return collect($this->configurations);
    }

    /**
     * @return ConsoleKernel
     */
    public function getKernel(): ConsoleKernel
    {
        return $this->app->make(ConsoleKernel::class);
    }

    /**
     * @return Application
     */
    public function getApplication(): Application
    {
        return $this->app;
    }
}
