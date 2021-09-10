<?php

declare(strict_types=1);

namespace VDauchy\LaravelZeroPortable;

use Webmozart\Assert\Assert;

class Application extends \LaravelZero\Framework\Application
{
    /**
     * @param  string  $basePath
     * @param  string  $appNamespace
     * @return static
     */
    public static function withNamespace(string $basePath, string $appNamespace): static
    {
        Assert::stringNotEmpty($appNamespace);

        return tap(new static($basePath), fn (self $app) => $app->namespace = $appNamespace);
    }
}
