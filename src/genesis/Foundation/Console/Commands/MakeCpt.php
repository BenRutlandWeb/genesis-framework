<?php

namespace Genesis\Foundation\Console\Commands;

use Genesis\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeCpt extends GeneratorCommand
{
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Command';

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
     * Handle making the model
     *
     * @param string $name
     *
     * @return void
     */
    protected function handle(): void
    {
        parent::handle();

        $name = $this->argument('name');

        if ($this->option('model') && $this->option('force')) {
            $this->call("make:model {$name} --posttype --force");
        } else if ($this->option('model')) {
            $this->call("make:model {$name} --posttype");
        }
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);

        [$public, $archive, $gutenberg, $icon] = $this->userFeedback();

        return str_replace(
            ['{{ class }}', '{{ name }}', '{{ public }}', '{{ archive }}', '{{ gutenberg }}', '{{ icon }}'],
            [$name, Str::lower($name), $public, $archive, $gutenberg, $icon],
            $stub
        );
    }

    /**
     * Ask a series of questions
     *
     * @return array
     */
    protected function userFeedback(): array
    {
        return [
            $this->confirm('Is public:') ? 'true' : 'false',
            $this->confirm('Has archive:') ? 'true' : 'false',
            $this->confirm('Use block editor:') ? 'true' : 'false',
            $this->ask('Icon:'),
        ];
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
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Cpts';
    }
}
