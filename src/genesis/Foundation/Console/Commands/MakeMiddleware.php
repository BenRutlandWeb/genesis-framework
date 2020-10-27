<?php

namespace Genesis\Foundation\Console\Commands;

use Genesis\Console\GenerateCommand;
use Illuminate\Support\Str;

class MakeMiddleware extends GenerateCommand
{
    /**
     * The command signature.
     *
     * @var string
     */
    protected $signature = 'make:middleware {name : The name of the middleware}
                                            {--force : Overwrite the middleware if it exists}';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Make a new middleware class';

    /**
     * Handle the command call.
     *
     * @return void
     */
    protected function handle(): void
    {
        $name = Str::studly($this->argument('name'));

        $path = $this->getPath($name);

        if ($this->files->exists($path) && !$this->option('force')) {
            $this->error('Middleware already exists!');
        }

        $this->makeDirectory($path);

        $stub = $this->files->get($this->getStub());

        $this->files->put($path, str_replace('{{ class }}', $name, $stub));

        $this->success('Middleware created');
    }

    /**
     * Get the stub path.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/stubs/middleware.stub';
    }

    /**
     * Resolve the filepath.
     *
     * @param string $name The name of the class.
     *
     * @return string
     */
    protected function getPath(string $name): string
    {
        return app()->appPath("/Middleware/{$name}.php");
    }
}
