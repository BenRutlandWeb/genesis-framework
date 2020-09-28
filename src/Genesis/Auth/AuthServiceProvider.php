<?php

namespace Genesis\Auth;

use Genesis\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('auth', function () {
            return new \Genesis\Auth\Auth();
        });
    }
}
