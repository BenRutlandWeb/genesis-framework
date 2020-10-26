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
     * @param string   $action
     * @param callable $callback
     *
     * @return void
     */
    public function match(array $methods, string $action, callable $callback): void
    {
        foreach ($methods as $method) {
            new ApiRoute($method, $action, $callback, $this);
        }
    }

    public function xresource(string $action, string $callback)
    {
        new ApiRoute('GET', $action, [$callback, 'index'], $this);
        new ApiRoute('GET', $action . '/create', [$callback, 'create'], $this);
        new ApiRoute('POST', $action, [$callback, 'store'], $this);
        new ApiRoute('GET', $action . '/{id}', [$callback, 'show'], $this);
        new ApiRoute('GET', $action . '/{id}/edit', [$callback, 'edit'], $this);
        new ApiRoute('PUT', $action . '/{id}', [$callback, 'update'], $this);
        new ApiRoute('PATCH', $action . '/{id}', [$callback, 'update'], $this);
        new ApiRoute('DELETE', $action . '/{id}', [$callback, 'destroy'], $this);
    }

    /**
     * Create a resource route
     *
     * @param string $action
     * @param string $callback
     *
     * @return void
     */
    public function resource(string $action, string $callback): void
    {
        $this->get($action, [$callback, 'index']);
        $this->get($action . '/create', [$callback, 'create']);
        $this->post($action, [$callback, 'store']);
        $this->get($action . '/{id}', [$callback, 'show']);
        $this->get($action . '/{id}/edit', [$callback, 'edit']);
        $this->match(['PUT', 'PATCH'], $action . '/{id}', [$callback, 'destroy']);
        $this->delete($action . '/{id}', [$callback, 'destroy']);
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
            return new ApiRoute($method, $params[0], $params[1], $this);
        }
        return (new RouteRegistrar($this))->$method(...$params);
    }
}
