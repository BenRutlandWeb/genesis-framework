<?php

namespace Genesis\Foundation\Console\Commands;

use Genesis\Console\GenerateCommand;

class MakeProvider extends GenerateCommand
{
    /**
     * The command signature.
     *
     * @var string
     */
    protected $signature = 'make:provider {name : The name of provider}
                                          {--force : Overwrite the provider if it exists}';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Make a provider';

    /**
     * Handle the command call.
     *
     * @return void
     */
    protected function handle(): void
    {
        $name = $this->argument('name');

        $path = $this->getPath($name);

        if ($this->files->exists($path) && !$this->option('force')) {
            $this->error('Provider already exists!');
        }

        $this->makeDirectory($path);

        $stub = $this->files->get($this->getStub());

        $stub = str_replace('{{ class }}', $name, $stub);

        $this->files->put($path, $stub);

        $this->success('Provider created');
    }

    /**
     * Get the stub path.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/stubs/provider.stub';
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
        return get_template_directory() . "/app/Providers/{$name}.php";
    }
}
