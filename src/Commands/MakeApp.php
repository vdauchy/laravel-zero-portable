<?php

declare(strict_types=1);

namespace VDauchy\LaravelZeroPortable\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeApp extends Command
{
    /**
     * @var string
     */
    protected $name = 'make:app';

    /**
     * @var string
     */
    protected $description = 'Create a new cli application';

    /**
     * @var string
     */
    protected string $type = 'Cli application file';

    /**
     * @param  Filesystem  $files
     */
    public function __construct(private Filesystem $files)
    {
        parent::__construct();
    }

    /**
     * @return false|void
     */
    public function handle()
    {
        $name = trim($this->argument('name'));

        $path = $this->getPath($name);

        if (! $this->hasOption('force') && $this->files->exists($path)) {
            $this->error("{$this->type} already exists!");

            return false;
        }

        $this->makeDirectory($path);

        $this->files->put($path, $this->files->get($this->getStub()));

        $this->files->chmod($path, 0755);

        $this->info("{$this->type} created successfully.");
    }

    /**
     * @return string
     */
    protected function getStub(): string
    {
        $relativePath = '/stubs/app.stub';

        return file_exists($customPath = $this->laravel->basePath(trim($relativePath, '/')))
            ? $customPath
            : __DIR__ . $relativePath;
    }

    /**
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the cli application'],
        ];
    }

    /**
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['path', null, InputOption::VALUE_OPTIONAL, 'The destination path', null],
        ];
    }

    /**
     * @param  string  $name
     * @return string
     */
    protected function getPath(string $name): string
    {
        return $this->laravel->basePath($this->option('path') . DIRECTORY_SEPARATOR . basename(str_replace('\\', '/', $name)));
    }

    /**
     * @param $path
     * @return string
     */
    protected function makeDirectory($path): string
    {
        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }

        return $path;
    }
}
