<?php

namespace Genesis\Database;

use Genesis\Support\ServiceProvider;
use WPEloquent\Database;

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
