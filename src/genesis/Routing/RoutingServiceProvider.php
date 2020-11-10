<?php

namespace Genesis\Routing;

use Illuminate\Support\ServiceProvider;

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
            return new UrlGenerator($app['request']);
        });
        $this->app->singleton(AjaxRouter::class);

        $this->app->singleton(RestRouter::class);

        $this->app->singleton(\Illuminate\Contracts\Routing\Registrar::class, RestRouter::class);
    }
}
