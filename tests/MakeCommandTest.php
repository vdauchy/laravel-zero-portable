<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use VDauchy\LaravelZeroPortable\Commands\MakeCommand;

beforeEach(function () {
    $this->refreshApplicationWith(
        baseDir: data_dir(),
        appNamespace: app_namespace(),
        commands: [
            MakeCommand::class
        ]);

    File::delete(data_dir('composer.json'));
    File::delete(data_dir('app/Console/Commands/CommandFoo.php'));
    File::deleteDirectory(data_dir('app'));
    File::deleteDirectory(data_dir('src'));
});

it('can make a new command in empty directory', function () {
    Artisan::call('make:command', ['name' => 'CommandFoo']);

    $path = data_dir('app/Console/Commands/CommandFoo.php');

    expect(File::exists($path))->toBeTrue();
    expect(File::get($path))
        ->toContain('namespace VDauchy\LaravelZeroPortable\Tests\Console\Commands;')
        ->toContain('class CommandFoo extends Command')
        ->toContain('protected $description = \'Command description\';');
});

it('can make a new command in src directory', function () {
    File::makeDirectory(data_dir('src'));

    Artisan::call('make:command', ['name' => 'CommandFoo']);

    $path = data_dir('src/Commands/CommandFoo.php');

    expect(File::exists($path))->toBeTrue();
    expect(File::get($path))
        ->toContain('namespace VDauchy\LaravelZeroPortable\Tests\Commands;')
        ->toContain('class CommandFoo extends Command')
        ->toContain('protected $description = \'Command description\';');
});

it('can make a new command with namespace from composer.json', function () {

    File::makeDirectory(data_dir('src'));
    File::put(data_dir('composer.json'), json_encode([
        "autoload" => [
            "psr-4" => [
                "Some\\Custom\\Namespace\\" => "src"
            ]
        ],
    ]));
    $this->refreshApplicationWith(baseDir: data_dir(), appNamespace: null);

    Artisan::call('make:command', ['name' => 'CommandFoo']);

    $path = data_dir('src/Commands/CommandFoo.php');

    expect(File::exists($path))->toBeTrue();
    expect(File::get($path))
        ->toContain('namespace Some\Custom\Namespace\Commands;')
        ->toContain('class CommandFoo extends Command')
        ->toContain('protected $description = \'Command description\';');
});
