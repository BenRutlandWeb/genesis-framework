<?php

namespace Genesis\Foundation\Console\Commands;

use Genesis\Console\GenerateCommand;
use Illuminate\Support\Str;

class MakeMail extends GenerateCommand
{
    /**
     * The command signature.
     *
     * @var string
     */
    protected $signature = 'make:mail {name : The name of the mailable}
                                      {--force : Overwrite the mailable if it exists}';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Make a mailable';

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
            $this->error('Mailable already exists!');
        }

        $this->makeDirectory($path);

        $stub = $this->files->get($this->getStub());

        $this->files->put($path, str_replace('{{ class }}', $name, $stub));

        $this->success('Mailable created');
    }

    /**
     * Get the stub path.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/stubs/mail.stub';
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
        return app()->appPath("/Mail/{$name}.php");
    }
}
