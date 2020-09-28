<?php

namespace Genesis\Console;

use Genesis\Foundation\Console\Commands\MakeModel;
use Genesis\Foundation\Console\Commands\RoyalMail;
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
        # code...
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
        $this->app->instance('command.make.model', new MakeModel($this->app->make('files')));

        $this->app->instance('command.royalmail', new RoyalMail());
    }
}
