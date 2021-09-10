<?php

use VDauchy\LaravelZeroPortable\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

function data_dir(string $path = '') {
    return implode(DIRECTORY_SEPARATOR, array_filter([__DIR__, '_data', $path]));
}

function app_namespace(): string {
    return 'VDauchy\\LaravelZeroPortable\\Tests';
}