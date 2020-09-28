<?php

namespace Genesis\Foundation\Console\Commands;

use Genesis\Console\GenerateCommand;

class MakeCommand extends GenerateCommand
{
    /**
     * The command signature.
     *
     * @var string
     */
    protected $signature = 'make:command {name : The name of command}
                                       {--force : Overwrite the command if it exists}';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Make a command';

    /**
     * Handle the command call.
     *
     * @return void
     */
    protected function handle(): void
    {
        $name = $this->argument('name');

        if (!$name) {
            $this->error('<name> argument is missing');
        }

        $path = $this->getPath($name);

        if ($this->files->exists($path) && !$this->option('force')) {
            $this->error('Command already exists!');
        }

        $this->makeDirectory($path);

        $stub = $this->files->get($this->getStub());

        $stub = str_replace(['{{ class }}', '{{ command }}'], [$name, strtolower($name)], $stub);

        $this->files->put($path, $stub);

        $this->success('Command created');
    }

    /**
     * Get the stub path.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/stubs/command.stub';
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
        return get_template_directory() . "/app/Commands/{$name}.php";
    }
}
