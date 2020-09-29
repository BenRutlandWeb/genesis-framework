<?php

namespace Genesis\Database;

use Genesis\Database\Database;
use Genesis\Support\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->instance('db', Database::connect());
    }
}
