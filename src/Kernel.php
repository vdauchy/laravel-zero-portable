<?php

declare(strict_types=1);

namespace VDauchy\LaravelZeroPortable;

use Illuminate\Foundation\Bootstrap\BootProviders;
use Illuminate\Foundation\Bootstrap\HandleExceptions;
use LaravelZero\Framework\Bootstrap\CoreBindings;
use LaravelZero\Framework\Bootstrap\LoadConfiguration;
use LaravelZero\Framework\Bootstrap\LoadEnvironmentVariables;
use LaravelZero\Framework\Bootstrap\RegisterFacades;

class Kernel extends \LaravelZero\Framework\Kernel
{
    /**
     * @var array
     */
    protected $developmentCommands = [
    ];

    /**
     * @var array
     */
    protected $developmentOnlyCommands = [
    ];

    /**
     * The application's bootstrap classes.
     *
     * @var array<string>
     */
    protected $bootstrappers = [
        CoreBindings::class,
        LoadEnvironmentVariables::class,
        LoadConfiguration::class,
        HandleExceptions::class,
        RegisterFacades::class,
        RegisterProviders::class,
        BootProviders::class,
    ];
}
