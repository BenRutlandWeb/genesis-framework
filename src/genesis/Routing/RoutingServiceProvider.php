<?php

namespace Genesis\Routing;

use Genesis\Support\Facades\Ajax;
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

        $this->app->singleton('router.api', function ($app) {
            return new ApiRouter($app);
        });
    }

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        Ajax::middleware('ajax')->group(base_path('routes/ajax.php'));

        $this->app['events']->listen('rest_api_init', function () {
            $this->app->make('router.api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));
        });
    }
}
