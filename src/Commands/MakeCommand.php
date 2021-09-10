<?php

declare(strict_types=1);

namespace VDauchy\LaravelZeroPortable\Commands;

use Illuminate\Foundation\Console\ConsoleMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

use function ucfirst;

class MakeCommand extends ConsoleMakeCommand
{
    /**
     * @var string
     */
    protected $description = 'Create a new command';

    /**
     * @return string
     */
    protected function getNameInput(): string
    {
        return ucfirst(parent::getNameInput());
    }

    /**
     * @return string
     */
    protected function getStub(): string
    {
        $relativePath = '/stubs/command.stub';

        return file_exists($customPath = $this->laravel->basePath(trim($relativePath, '/')))
            ? $customPath
            : __DIR__ . $relativePath;
    }

    /**
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        if ($path = $this->option('path')) {
            return str_replace(DIRECTORY_SEPARATOR, '\\', "{$rootNamespace}\\$path");
        }

        if (file_exists($this->laravel->basePath("src"))) {
            return str_replace(DIRECTORY_SEPARATOR, '\\', "{$rootNamespace}\\Commands");
        }

        return parent::getDefaultNamespace($rootNamespace);
    }

    /**
     * @return array
     */
    protected function getOptions(): array
    {
        return array_merge(parent::getOptions(), [
            ['path', null, InputOption::VALUE_OPTIONAL, 'The destination path', null],
        ]);
    }

    /**
     * @param  string  $name
     * @return string
     */
    protected function getPath($name): string
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        $file = str_replace('\\', '/', $name) . '.php';

        if ($this->option('path')) {
            return $this->laravel->basePath($file);
        }

        if (file_exists($this->laravel->basePath("src"))) {
            return $this->laravel->basePath("src/{$file}");
        }

        return $this->laravel->basePath("app/{$file}");
    }
}
