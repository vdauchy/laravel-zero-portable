<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use VDauchy\LaravelZeroPortable\Commands\MakeApp;

beforeEach(function () {
    $this->refreshApplicationWith(
        baseDir: data_dir(),
        appNamespace: 'VDauchy\\LaravelZeroPortable\\Tests\\App',
        commands: [
            MakeApp::class
        ]);
});

afterEach(function () {
    File::delete(data_dir('foo'));
    File::deleteDirectory(data_dir('custom_directory'));
});

it('can make a new app in base directory', function () {

    Artisan::call('make:app', ['name' => 'foo']);

    $path = data_dir('foo');

    expect(File::exists($path))->toBeTrue();
    expect(File::get($path))
        ->toContain('#!/usr/bin/env php')
        ->toContain('$autoloader = require __DIR__ . \'/vendor/autoload.php\';')
        ->toContain('LaravelZeroPortable::buildDefault');
});

it('can make a new app in optional directory', function () {

    Artisan::call('make:app', ['name' => 'foo', '--path' => 'custom_directory']);

    $path = data_dir('custom_directory/foo');

    expect(File::exists($path))->toBeTrue();
    expect(File::get($path))
        ->toContain('#!/usr/bin/env php')
        ->toContain('$autoloader = require __DIR__ . \'/vendor/autoload.php\';')
        ->toContain('LaravelZeroPortable::buildDefault');
});
