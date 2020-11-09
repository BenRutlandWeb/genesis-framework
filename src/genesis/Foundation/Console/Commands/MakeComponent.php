<?php

namespace Genesis\Foundation\Console\Commands;

use Genesis\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeComponent extends GeneratorCommand
{
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Component';

    /**
     * The command signature.
     *
     * @var string
     */
    protected $signature = 'make:component {name : The component name}
                                           {--force : Overwrite the component if it exists}';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Make a component';

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

        $this->writeView();
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        return str_replace(
            '{{ view }}',
            'view(\'components.' . $this->getView() . '\')',
            parent::buildClass($name)
        );
    }

    /**
     * Write the view for the component.
     *
     * @return void
     */
    protected function writeView()
    {
        $view = $this->getView();

        $path = resource_path('views') . '/' . str_replace('.', '/', 'components.' . $view);

        $this->makeDirectory($path);

        $this->files->put($path . '.blade.php', '<div> <!-- something --> </div>');
    }

    /**
     * Get the view name relative to the components directory.
     *
     * @return string view
     */
    protected function getView()
    {
        $name = str_replace('\\', '/', $this->argument('name'));

        return collect(explode('/', $name))
            ->map(function ($part) {
                return Str::kebab($part);
            })
            ->implode('.');
    }

    /**
     * Get the stub path.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/stubs/component.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\View\Components';
    }
}
