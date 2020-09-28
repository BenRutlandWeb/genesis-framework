<?php

namespace Genesis\Console;

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
            return new \Genesis\Foundation\Console\Commands\MakeModel($app->make('files'));
        });
        $this->app->instance('command.royalmail', new \Genesis\Foundation\Console\Commands\RoyalMail());
    }
}
