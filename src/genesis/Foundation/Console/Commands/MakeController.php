<?php

namespace Genesis\Foundation\Console\Commands;

use Genesis\Console\GenerateCommand;
use Illuminate\Support\Str;

class MakeController extends GenerateCommand
{
    /**
     * The command signature.
     *
     * @var string
     */
    protected $signature = 'make:controller {name : The name of the controller}
                                            {--resource : Generate a resource controller class}
                                            {--force : Overwrite the controller if it exists}';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Make a controller';

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
            $this->error('Controller already exists!');
        }

        $this->makeDirectory($path);

        $stub = $this->files->get($this->getStub());

        $this->files->put($path, str_replace('{{ class }}', $name, $stub));

        $this->success('Controller created');
    }

    /**
     * Get the stub path.
     *
     * @return string
     */
    protected function getStub(): string
    {
        if ($this->option('resource')) {
            return __DIR__ . '/stubs/controller.resource.stub';
        }
        return __DIR__ . '/stubs/controller.stub';
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
        return app()->appPath("/Controllers/{$name}.php");
    }
}
