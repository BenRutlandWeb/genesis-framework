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
        if (!class_exists('WP_CLI')) {
            return;
        }
        $this->app->singleton('command.make.model', function ($app) {
            return new MakeModel($app->make('files'));
        });

        $this->app->singleton('command.royalmail', function () {
            return new RoyalMail();
        });
    }

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->make('command.make.model');
        $this->app->make('command.royalmail');
    }
}
