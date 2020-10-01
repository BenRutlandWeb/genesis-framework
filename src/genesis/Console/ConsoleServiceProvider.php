<?php

namespace Genesis\Console;

use Genesis\Console\Console;
use Genesis\Foundation\Console\Commands\MakeModel;
use Genesis\Foundation\Console\Commands\MakeProvider;
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
            return new Console();
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
        $this->app->instance('command.make.model', new MakeModel($files));
        $this->app->instance('command.make.provider', new MakeProvider($files));

        include $this->app->appPath('routes/console.php');
    }
}
