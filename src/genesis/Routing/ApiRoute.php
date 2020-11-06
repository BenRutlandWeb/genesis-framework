<?php

namespace Genesis\Routing;

use Genesis\Foundation\Application;
use Genesis\Routing\Router;
use WP_REST_Request;

class ApiRoute
{
    /**
     * The route method
     *
     * @var string
     */
    protected $method;

    /**
     * The route URL
     *
     * @var string
     */
    protected $route;

    /**
     * The route callback
     *
     * @var callable|string
     */
    protected $callback;

    /**
     * The router instance
     *
     * @var \Genesis\Routing\Router
     */
    protected $router;

    /**
     * The app instance
     *
     * @var \Genesis\Foundation\Application
     */
    protected $app;

    /**
     * Create the route instance
     *
     * @param string                  $method
     * @param string                  $route
     * @param callable|string         $callback
     * @param \Genesis\Routing\Router $router
     */
    public function __construct(string $method, string $route, $callback, Router $router, Application $app)
    {
        $this->method = $method;
        $this->route = $route;
        $this->callback = $callback;
        $this->router = $router;
        $this->app = $app;

        $this->register();
    }

    /**
     * Check the user auth and create the ajax actions
     *
     * @return void
     */
    public function register(): void
    {
        if ($method = $this->resolveMethod($this->method)) {
            register_rest_route('api', $this->resolveURL(), [
                'methods'  => $method,
                'callback' => $this->resolveCallback(),
            ]);
        }
    }

    /**
     * Resolve the method
     *
     * @param string $route
     *
     * @return string|false
     */
    protected function resolveMethod(string $route)
    {
        $route = strtoupper($route);

        if (in_array($route, ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'])) {
            return $route;
        }
        return false;
    }

    /**
     * Resolve the route URL
     *
     * @return string
     */
    protected function resolveURL(): string
    {
        return preg_replace('@\/\{([\w]+?)(\?)?\}@', '\/?(?P<$1>[a-zA-Z0-9-]+)$2', $this->router->getPrefix($this->route));
    }

    /**
     * Resolve the callback
     *
     * @return callable
     */
    public function resolveCallback(): callable
    {
        return function (WP_REST_Request $request) {

            $this->app['request']->merge($request->get_url_params());

            return $this->app->call($this->callback, $request->get_url_params());
        };
    }
}
