<?php

namespace Genesis\Routing;

use Closure;
use Genesis\Foundation\Application;
use Genesis\Routing\RouteRegistrar;

class Router
{
    /**
     * The application instance
     *
     * @var \Genesis\Foundation\Application
     */
    protected $app;

    /**
     * The router group attributes
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Assign the app to the instance
     *
     * @param \Genesis\Foundation\Application $app
     *
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle group calls
     *
     * @param array           $attributes
     * @param \Closure|string $callback
     *
     * @return void
     */
    public function group(array $attributes, $routes): void
    {
        $this->attributes[] = $attributes;

        if ($routes instanceof Closure) {
            $routes($this);
        } else {
            require $routes;
        }
        array_pop($this->attributes);
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
        foreach ($this->attributes as $attribute) {
            if (in_array($name, $attribute['middleware'])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Return the prefix
     *
     * @return string
     */
    public function getPrefix(string $suffix, $sep = '/'): string
    {
        $prefix = implode($sep, array_column($this->attributes, 'prefix'));

        return trim($prefix . $sep . $suffix, $sep);
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
