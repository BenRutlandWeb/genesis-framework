<?php

namespace Genesis\Auth;

use Illuminate\Support\ServiceProvider;

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
            return new Auth();
        });
    }

    /**
     * Add blade directives
     *
     * @return void
     */
    public function boot()
    {
        $blade = $this->app->make('blade.compiler');

        $blade->if('auth', function () {
            return $this->app['auth']->check();
        });
        $blade->if('guest', function () {
            return $this->app['auth']->guest();
        });
    }
}
