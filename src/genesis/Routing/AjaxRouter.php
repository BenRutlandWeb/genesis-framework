<?php

namespace Genesis\Routing;

use Genesis\Http\Request;
use Genesis\Routing\AjaxRoute;
use Illuminate\Routing\Pipeline;
use Illuminate\Routing\Router;

class AjaxRouter extends Router
{
    /**
     * Register a new AUTH ajax route with the router.
     *
     * @param string $uri
     * @param array|string|callable|null $action
     *
     * @return \Genesis\Routing\AjaxRoute
     */
    public function auth(string $uri, $action = null)
    {
        return $this->addRoute('AUTH', $uri, $action);
    }

    /**
     * Register a new GUEST ajax route with the router.
     *
     * @param string $uri
     * @param array|string|callable|null $action
     *
     * @return \Genesis\Routing\AjaxRoute
     */
    public function guest(string $uri, $action = null)
    {
        return $this->addRoute('GUEST', $uri, $action);
    }

    /**
     * Register a new AUTH and GUEST ajax route with the router.
     *
     * @param string $uri
     * @param array|string|callable|null $action
     *
     * @return \Genesis\Routing\AjaxRoute
     */
    public function listen(string $uri, $action = null)
    {
        return $this->addRoute(['AUTH', 'GUEST'], $uri, $action);
    }

    /**
     * Create a new AjaxRoute object.
     *
     * @param  array|string  $methods
     * @param  string  $uri
     * @param  mixed  $action
     *
     * @return \Genesis\Routing\AjaxRoute
     */
    public function newRoute($methods, $uri, $action)
    {
        return (new AjaxRoute($methods, $uri, $action))
            ->setRouter($this)
            ->setContainer($this->container)
            ->dispatch();
    }

    /**
     * Listen to the WordPress AJAX events
     *
     * @param  \Genesis\Routing\AjaxRoute  $route
     *
     * @return mixed
     */
    public function dispatchAjaxRoute(AjaxRoute $route)
    {
        if (in_array('AUTH', $route->methods)) {
            $this->events->listen("wp_ajax_{$route->uri}", function () use ($route) {
                die($this->runAjaxRouteWithinStack($route, $this->container['request']));
            });
        }
        if (in_array('GUEST', $route->methods)) {
            $this->events->listen("wp_ajax_nopriv_{$route->uri}", function () use ($route) {
                die($this->runAjaxRouteWithinStack($route, $this->container['request']));
            });
        }

        return $route;
    }

    /**
     * Run the given route within a Stack "onion" instance.
     *
     * @param  \Genesis\Routing\AjaxRoute  $route
     * @param  \Genesis\Http\Request  $request
     *
     * @return mixed
     */
    protected function runAjaxRouteWithinStack(AjaxRoute $route, Request $request)
    {
        $shouldSkipMiddleware = $this->container->bound('middleware.disable') &&
            $this->container->make('middleware.disable') === true;

        $middleware = $shouldSkipMiddleware ? [] : $this->gatherRouteMiddleware($route);

        return (new Pipeline($this->container))
            ->send($request)
            ->through($middleware)
            ->then(function ($request) use ($route) {
                return $this->container->call($route->getController(), [$request]);
            });
    }
}
