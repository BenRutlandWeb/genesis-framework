<?php

namespace Genesis\Routing;

use Illuminate\Routing\Router;
use Illuminate\Support\Str;
use WP_REST_Request;

class RestRouter extends Router
{
    /**
     * Create a new RestRoute object.
     *
     * @param  array|string  $methods
     * @param  string  $uri
     * @param  mixed  $action
     *
     * @return \Genesis\Routing\RestRoute
     */
    public function newRoute($methods, $uri, $action)
    {
        return (new RestRoute($methods, $uri, $action))
            ->setRouter($this)
            ->setContainer($this->container)
            ->dispatch();
    }

    /**
     * Register the WordPress REST route
     *
     * @param  \Genesis\Routing\RestRoute  $route
     *
     * @return mixed
     */
    public function dispatchRestRoute(RestRoute $route)
    {
        register_rest_route($this->resolveNamespace(), $this->resolveURL($route), [
            'methods'  => $route->methods,
            'callback' => function (WP_REST_Request $request) use ($route) {
                $this->sendResponse($route->bindRestParameters($request, $this->container['request']));
            },
            'permission_callback' => '__return_true',
        ]);

        return $route;
    }

    /**
     * Send the response to the REST handler
     *
     * @param \Genesis\Routing\RestRoute $route
     *
     * @return void
     */
    protected function sendResponse(RestRoute $route): void
    {
        die($this->runRoute($this->container['request'], $route));
    }

    /**
     * Create a response instance from the given value.
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @param  mixed  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function prepareResponse($request, $response)
    {
        return $response;
    }

    /**
     * Resolve the route URL
     *
     * @return string
     */
    protected function resolveURL(RestRoute $route): string
    {
        $url = Str::after($route->uri, $this->resolveNamespace());

        return preg_replace('@\/\{([\w]+?)(\?)?\}@', '\/?(?P<$1>[a-zA-Z0-9-]+)$2', $url);
    }

    /**
     * Get the namespace from the first prefix or "api" if not available
     *
     * @return string
     */
    protected function resolveNamespace(): string
    {
        if ($this->hasGroupStack()) {
            $first = $this->groupStack[0];

            return $first['prefix'] ?? 'api';
        }

        return 'api';
    }
}
