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
        $this->app->instance('command.make.model', new \Genesis\Foundation\Console\Commands\MakeModel());
        $this->app->instance('command.royalmail', new \Genesis\Foundation\Console\Commands\RoyalMail());
    }
}
