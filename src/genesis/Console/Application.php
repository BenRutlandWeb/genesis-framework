<?php

namespace Genesis\Console;

use Closure;
use ReflectionClass;
use Genesis\Console\Command;
use Genesis\Contracts\Foundation\Application as ApplicationContract;
use Genesis\Foundation\Console\Commands\ClosureCommand;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;

class Application
{
    /**
     * The registered commands
     *
     * @var array
     */
    protected $commands = [];

    /**
     * The app instance
     *
     * @var \Genesis\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Bind the application instance to console application
     *
     * @param \Genesis\Contracts\Foundation\Application $app
     *
     * @return void
     */
    public function __construct(ApplicationContract $app)
    {
        $this->app = $app;
    }

    /**
     * Register a closure command.
     *
     * @param  string  $signature The command signature
     * @param  Closure $callback  The callback to run
     * @return mixed
     */
    public function command(string $signature, Closure $callback)
    {
        $command = new ClosureCommand($signature, $callback);

        $this->add($command);

        return $command;
    }

    /**
     * Add a command to the application.
     *
     * @param \Genesis\Console\Command $command
     * @return void
     */
    public function add(Command $command): void
    {
        $this->commands[] = $command;
    }

    /**
     * Register each of the commands
     *
     * @return void
     */
    public function boot(): void
    {
        foreach ($this->commands as $command) {
            $command->boot();
        }
    }

    /**
     * Load the console commands
     *
     * @param string|array $paths
     *
     * @return void
     */
    public function load($paths): void
    {
        $paths = array_unique(Arr::wrap($paths));

        $paths = array_filter($paths, function ($path) {
            return is_dir($path);
        });

        if (empty($paths)) {
            return;
        }

        $namespace = $this->app->getNamespace();

        foreach ((new Finder)->in($paths)->files() as $command) {
            $command = $namespace . str_replace(
                ['/', '.php'],
                ['\\', ''],
                Str::after($command->getPathname(), realpath(app_path()) . DIRECTORY_SEPARATOR)
            );

            if (
                is_subclass_of($command, Command::class) &&
                !(new ReflectionClass($command))->isAbstract()
            ) {
                $this->add(new $command());
            }
        }
    }
}
