<?php

declare(strict_types=1);

namespace VDauchy\LaravelZeroPortable;

use Illuminate\Support\Collection;

interface LaravelZeroPortableInterface
{
    public function getConfigurations(): Collection;
}
