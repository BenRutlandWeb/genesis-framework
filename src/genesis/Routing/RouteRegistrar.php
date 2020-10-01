<?php

namespace Genesis\Routing;

use Closure;
use Genesis\Routing\Router;

class RouteRegistrar
{
    /**
     * The registered middleware
     *
     * @var array
     */
    protected $middleware = [];

    /**
     * Assign the router to the instance
     *
     * @param \Genesis\Routing\Router $router
     *
     * @return void
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Assign the middleware to the registrar
     *
     * @param array|string $middleware
     *
     * @return \Genesis\Routing\RouteRegistrar
     */
    public function middleware($middleware): RouteRegistrar
    {
        $this->middleware = is_array($middleware) ? $middleware : [$middleware];

        return $this;
    }

    /**
     * Handle group calls to the router
     *
     * @param \Closure|string $closure
     *
     * @return void
     */
    public function group($closure): void
    {
        $this->router->group($this->middleware, $closure);
    }
}
