<?php

namespace Genesis\Console;

use Genesis\Console\Application;
use Genesis\Support\ServiceProvider;

class ConsoleServiceProvider extends ServiceProvider
{
    /**
     * The pre-built commands
     *
     * @var array
     */
    protected $commands = [
        \Genesis\Foundation\Console\Commands\MakeController::class,
        \Genesis\Foundation\Console\Commands\MakeModel::class,
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('console', function () {
            return new Application();
        });
    }

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot(): void
    {
        if (!class_exists('WP_CLI')) {
            return;
        }
        $console = $this->app->make('console');
        $files = $this->app->make('files');

        foreach ($this->commands as $command) {
            $console->add(new $command($files));
        }
        if ($files->exists($path = $this->app->appPath('routes/console.php'))) {
            include $path;
        }
        $console->boot();
    }
}
