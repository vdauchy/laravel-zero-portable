<?php

declare(strict_types=1);

namespace VDauchy\LaravelZeroPortable\Configs;

class App
{
    public function __construct(
        public string $name,
        public string $version,
        public string $env,
        public array $providers,
    ) {
    }
}
