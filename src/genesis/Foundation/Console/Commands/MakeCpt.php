<?php

namespace Genesis\Foundation\Console\Commands;

use Genesis\Console\GenerateCommand;
use Illuminate\Support\Str;

class MakeCpt extends GenerateCommand
{
    /**
     * The command signature.
     *
     * @var string
     */
    protected $signature = 'make:cpt {name : The post type name}
                                     {--model : Make a model for this post type}
                                     {--force : Overwrite the controller if it exists}';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Make a custom post type';

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
            $this->error('Post type already exists!');
        }

        $this->makeDirectory($path);

        $stub = $this->files->get($this->getStub());

        [$plural, $single, $icon] = $this->userFeedback();

        $this->files->put($path, str_replace(
            ['{{ class }}', '{{ name }}', '{{ plural }}', '{{ singular }}', '{{ icon }}'],
            [$name, Str::lower($name), $plural, $single, $icon],
            $stub
        ));

        $this->success('Post type created');

        $this->handleModel($name);
    }

    /**
     * Ask a series of questions
     *
     * @return array
     */
    public function userFeedback(): array
    {
        return [
            $this->ask('Plural:'),
            $this->ask('Singular:'),
            $this->ask('Icon:'),
        ];
    }

    /**
     * Handle making the model
     *
     * @param string $name
     *
     * @return void
     */
    protected function handleModel(string $name): void
    {
        if ($this->option('model') && $this->option('force')) {
            $this->call("make:model {$name} --posttype --force");
        } else if ($this->option('model')) {
            $this->call("make:model {$name} --posttype");
        }
    }

    /**
     * Get the stub path.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/stubs/cpt.stub';
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
        return app()->appPath("Cpts/{$name}.php");
    }
}
