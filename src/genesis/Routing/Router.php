<?php

namespace Genesis\Routing;

use Closure;
use Genesis\Http\Request;
use Genesis\Routing\RouteRegistrar;

class Router
{
    /**
     * The router middleware
     *
     * @var array
     */
    protected $middleware = [];

    /**
     * Assign the request to the instance
     *
     * @param Request $request
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle group calls
     *
     * @param array           $middleware
     * @param \Closure|string $callback
     *
     * @return void
     */
    public function group(array $middleware, $routes): void
    {
        $this->middleware[] = $middleware;

        if ($routes instanceof Closure) {
            $routes($this);
        } else {
            require $routes;
        }
        array_pop($this->middleware);
    }

    /**
     * Check if the router has middleware
     *
     * @param string $name
     *
     * @return boolean
     */
    public function hasMiddleware(string $name): bool
    {
        foreach ($this->middleware as $middleware) {
            if (in_array($name, $middleware)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Forward calls to the route registrar
     *
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     */
    public function __call(string $method, array $args)
    {
        return (new RouteRegistrar($this))->$method(...$args);
    }
}
