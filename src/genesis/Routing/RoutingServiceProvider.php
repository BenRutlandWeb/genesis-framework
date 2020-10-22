<?php

namespace Genesis\Routing;

use Genesis\Support\ServiceProvider;

class RoutingServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('url', function ($app) {
            return new \Genesis\Routing\UrlGenerator($app->make('request'));
        });
        $this->app->singleton('router.ajax', function ($app) {
            return new \Genesis\Routing\AjaxRouter($app->make('request'));
        });
    }

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('router.ajax')
            ->middleware('ajax')
            ->group($this->app->basePath('routes/ajax.php'));
    }
}
