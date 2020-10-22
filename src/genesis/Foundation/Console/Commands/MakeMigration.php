<?php

namespace Genesis\Foundation\Console\Commands;

use Genesis\Console\GenerateCommand;
use Illuminate\Support\Str;

class MakeMigration extends GenerateCommand
{
    /**
     * The command signature.
     *
     * @var string
     */
    protected $signature = 'make:migration {name : The name of the migration}
                                           {--table : The name of the table}
                                           {--force : Overwrite the event if it exists}';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Make a migration';

    /**
     * Handle the command call.
     *
     * @return void
     */
    protected function handle(): void
    {
        $filename = Str::snake($this->argument('name'));
        $name = Str::studly($this->argument('name'));

        $table = $this->option('table');

        $path = $this->getPath(date('Y_m_d_His_') . $filename);

        if ($this->files->exists($path) && !$this->option('force')) {
            $this->error('Migration already exists!');
        }

        $this->makeDirectory($path);

        $stub = $this->files->get($this->getStub());

        $this->files->put($path, str_replace(['{{ class }}', '{{ table }}'], [$name, $table], $stub));

        $this->success('Migration created');
    }

    /**
     * Get the stub path.
     *
     * @return string
     */
    protected function getStub(): string
    {
        if ($this->option('table')) {
            return __DIR__ . '/stubs/migration.stub';
        }
        return __DIR__ . '/stubs/migration.create.stub';
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
        return app()->basePath("database/migrations/{$name}.php");
    }
}
