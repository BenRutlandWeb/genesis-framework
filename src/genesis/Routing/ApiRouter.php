<?php

namespace Genesis\Routing;

use Genesis\Routing\ApiRoute;
use Genesis\Routing\Router;

class ApiRouter extends Router
{
    /**
     * Register a route matching the passed methods
     *
     * @param array    $methods
     * @param string   $route
     * @param callable $callback
     *
     * @return void
     */
    public function match(array $methods, string $route, callable $callback): void
    {
        foreach ($methods as $method) {
            $this->newRoute($method, $route, $callback);
        }
    }

    /**
     * Create a resource route
     *
     * @param string $route
     * @param string $callback
     *
     * @return void
     */
    public function resource(string $route, string $callback): void
    {
        $this->get($route, [$callback, 'index']);
        $this->get($route . '/create', [$callback, 'create']);
        $this->post($route, [$callback, 'store']);
        $this->get($route . '/{id}', [$callback, 'show']);
        $this->get($route . '/{id}/edit', [$callback, 'edit']);
        $this->match(['PUT', 'PATCH'], $route . '/{id}', [$callback, 'update']);
        $this->delete($route . '/{id}', [$callback, 'destroy']);
    }

    /**
     * Create a new route
     *
     * @param string          $method
     * @param string          $route
     * @param callable|string $callback
     *
     * @return \Genesis\Routing\ApiRoute
     */
    public function newRoute(string $method, string $route, $callback): ApiRoute
    {
        return new ApiRoute($method, $route, $callback, $this, $this->app);
    }

    /**
     * Dynamically call method routes
     *
     * @param string $method
     * @param array  $params
     *
     * @return \Genesis\Routing\ApiRoute|\Genesis\Routing\RouteRegistrar
     */
    public function __call(string $method, array $params)
    {
        if (in_array($method, ['get', 'post', 'put', 'patch', 'delete'])) {
            return $this->newRoute($method, ...$params);
        }
        return (new RouteRegistrar($this))->$method(...$params);
    }
}
