<?php

declare(strict_types=1);

namespace VDauchy\LaravelZeroPortable;

use Illuminate\Foundation\Bootstrap\RegisterProviders as BaseRegisterProviders;
use LaravelZero\Framework\Application;
use LaravelZero\Framework\Components;
use LaravelZero\Framework\Contracts\BoostrapperContract;
use LaravelZero\Framework\Providers;
use LaravelZero\Framework\Providers\CommandRecorder\CommandRecorderServiceProvider;
use LaravelZero\Framework\Providers\NullLogger\NullLoggerServiceProvider;
use NunoMaduro\LaravelConsoleSummary\LaravelConsoleSummaryServiceProvider;
use NunoMaduro\LaravelConsoleTask\LaravelConsoleTaskServiceProvider;
use NunoMaduro\LaravelDesktopNotifier\LaravelDesktopNotifierServiceProvider;

/**
 * @internal
 */
class RegisterProviders implements BoostrapperContract
{
    /**
     * @var array<string>
     */
    protected $providers = [
        NullLoggerServiceProvider::class,
        Providers\Collision\CollisionServiceProvider::class,
        Providers\Cache\CacheServiceProvider::class,
        Providers\Filesystem\FilesystemServiceProvider::class,
        Providers\Composer\ComposerServiceProvider::class,
        LaravelDesktopNotifierServiceProvider::class,
        LaravelConsoleTaskServiceProvider::class,
        LaravelConsoleSummaryServiceProvider::class,
        CommandRecorderServiceProvider::class,
    ];

    /**
     * @var array<string>
     */
    protected $components = [
        Components\Log\Provider::class,
        Components\Logo\Provider::class,
        Components\Queue\Provider::class,
        Components\Database\Provider::class,
        Components\Menu\Provider::class,
        Components\Redis\Provider::class,
        Components\ScheduleList\Provider::class,
    ];

    /**
     * @param  Application  $app
     */
    public function bootstrap(Application $app): void
    {
        $app->make(BaseRegisterProviders::class)->bootstrap($app);

        collect($this->providers)
            ->merge(collect($this->components)->filter(fn (string $component) => (new $component($app))->isAvailable()))
            ->each(fn (string $serviceProviderClass) => $app->register($serviceProviderClass));
    }
}
