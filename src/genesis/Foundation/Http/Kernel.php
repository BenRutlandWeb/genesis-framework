<?php

namespace Genesis\Foundation\Http;

use Genesis\Routing\AjaxRouter;
use Genesis\Routing\RestRouter;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;

class Kernel
{
    /**
     * The application implementation.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The router instance.
     *
     * @var array
     */
    protected $routers = [];

    /**
     * The bootstrap classes for the application.
     *
     * @var array
     */
    protected $bootstrappers = [
        \Genesis\Foundation\Bootstrap\LoadConfiguration::class,
        \Genesis\Foundation\Bootstrap\RegisterFacades::class,
        \Genesis\Foundation\Bootstrap\RegisterProviders::class,
        \Genesis\Foundation\Bootstrap\BootProviders::class,
    ];

    /**
     * The application's middleware stack.
     *
     * @var array
     */
    protected $middleware = [];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [];

    /**
     * The priority-sorted list of middleware.
     *
     * Forces non-global middleware to always be in the given order.
     *
     * @var array
     */
    protected $middlewarePriority = [
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests::class,
        \Illuminate\Routing\Middleware\ThrottleRequests::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
    ];

    /**
     * Create a new HTTP kernel instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Genesis\Routing\AjaxRouter  $ajaxRouter
     * @param  \Genesis\Routing\RestRouter  $restRouter
     *
     * @return void
     */
    public function __construct(Application $app, AjaxRouter $ajaxRouter, RestRouter $restRouter)
    {
        $this->app = $app;
        $this->routers = [$ajaxRouter, $restRouter];

        $this->syncMiddlewareToRouters();
    }

    /**
     * Bootstrap the application for HTTP requests.
     *
     * @return void
     */
    public function bootstrap(): void
    {
        if (!$this->app->hasBeenBootstrapped()) {
            $this->app->bootstrapWith($this->bootstrappers());
        }
    }

    /**
     * Sync the current state of the middleware to the router.
     *
     * @return void
     */
    protected function syncMiddlewareToRouters(): void
    {
        foreach ($this->routers as $router) {
            $this->syncMiddlewareToRouter($router);
        }
    }

    /**
     * Sync the current state of the middleware to the router.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    protected function syncMiddlewareToRouter(Router $router): void
    {
        $router->middlewarePriority = $this->middlewarePriority;

        foreach ($this->middlewareGroups as $key => $middleware) {
            $router->middlewareGroup($key, $middleware);
        }

        foreach ($this->routeMiddleware as $key => $middleware) {
            $router->aliasMiddleware($key, $middleware);
        }
    }

    /**
     * Get the bootstrap classes for the application.
     *
     * @return array
     */
    protected function bootstrappers(): array
    {
        return $this->bootstrappers;
    }

    /**
     * Get the application's route middleware groups.
     *
     * @return array
     */
    public function getMiddlewareGroups(): array
    {
        return $this->middlewareGroups;
    }

    /**
     * Get the application's route middleware.
     *
     * @return array
     */
    public function getRouteMiddleware(): array
    {
        return $this->routeMiddleware;
    }

    /**
     * Get the Laravel application instance.
     *
     * @return \Illuminate\Contracts\Foundation\Application
     */
    public function getApplication(): Application
    {
        return $this->app;
    }
}
