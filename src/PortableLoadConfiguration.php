<?php

declare(strict_types=1);

namespace VDauchy\LaravelZeroPortable;

use Closure;
use Illuminate\Contracts\Config\Repository as RepositoryContract;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;

/**
 * @internal
 */
class PortableLoadConfiguration extends LoadConfiguration
{
    /**
     * @param  ApplicationContract  $app
     * @param  RepositoryContract  $repository
     */
    protected function loadConfigurationFiles(Application $app, RepositoryContract $repository)
    {
        $this->laravelZeroPortable($app)->getConfigurations()
            ->each(fn (Closure $closure, string $key) => $repository->set($key, (array)$closure()));
    }

    /**
     * @param  ApplicationContract  $app
     * @return LaravelZeroPortable
     */
    protected function laravelZeroPortable(Application $app): LaravelZeroPortableInterface
    {
        return $app->make(LaravelZeroPortableInterface::class);
    }
}
