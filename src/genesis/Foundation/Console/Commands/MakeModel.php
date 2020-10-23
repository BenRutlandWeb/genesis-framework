<?php

namespace Genesis\Foundation\Console\Commands;

use Genesis\Console\GenerateCommand;
use Illuminate\Support\Str;

class MakeModel extends GenerateCommand
{
    /**
     * The command signature.
     *
     * @var string
     */
    protected $signature = 'make:model {name : The name of model}
                                       {--posttype : Extend the posts table?}
                                       {--force : Overwrite the model if it exists}';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Make a model';

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
            $this->error('Model already exists!');
        }

        $this->makeDirectory($path);

        $stub = $this->files->get($this->getStub());

        $this->files->put($path, str_replace(['{{ class }}', '{{ table }}'], [$name, Str::snake($name)], $stub));

        $this->success('Model created');
    }

    /**
     * Get the stub path.
     *
     * @return string
     */
    protected function getStub(): string
    {
        if ($this->option('posttype')) {
            return __DIR__ . '/stubs/model.posttype.stub';
        }
        return __DIR__ . '/stubs/model.stub';
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
        return app()->appPath("Models/{$name}.php");
    }
}
