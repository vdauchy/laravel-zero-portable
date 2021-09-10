<?php

declare(strict_types=1);

namespace VDauchy\LaravelZeroPortable\Configs;

class Commands
{
    public function __construct(
        public string $default,
        public array $paths,
        public array $add,
        public array $hidden,
        public array $remove,
    ) {
    }
}
