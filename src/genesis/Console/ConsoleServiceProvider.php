<?php

namespace Genesis\Console;

use Genesis\Console\Application;
use Genesis\Support\ServiceProvider;

class ConsoleServiceProvider extends ServiceProvider
{
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
        $files = $this->app->make('files');

        if ($files->exists($console = $this->app->appPath('routes/console.php'))) {
            include $console;
        }
        $this->app->make('console')->boot();
    }
}
