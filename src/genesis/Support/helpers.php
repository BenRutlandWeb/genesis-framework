<?php

use Genesis\Auth\Auth;
use Genesis\Foundation\Application;
use Genesis\Http\Request;
use Genesis\Routing\URL;

if (!function_exists('app')) {
    /**
     * Return an instance of the app or an app binding.
     *
     * @return \Genesis\Foundation\Application|mixed
     */
    function app($id = null, ...$params)
    {
        if (is_null($id)) {
            return Application::getInstance();
        }
        return Application::getInstance()->make($id, ...$params);
    }
}

if (!function_exists('asset')) {
    /**
     * Return an instance of the app or an app binding.
     *
     * @param string $path
     * @param bool $absolute
     *
     * @return string
     */
    function asset(string $path, bool $absolute = true): string
    {
        return app('url')->asset($path, $absolute);
    }
}

if (!function_exists('auth')) {
    /**
     * Get the auth object.
     *
     * @return \Genesis\Auth\Auth
     */
    function auth(): Auth
    {
        return app('auth');
    }
}

if (!function_exists('ajax')) {
    /**
     * Return the ajax URL with the action
     *
     * @param string $action The ajax action
     *
     * @return string
     */
    function ajax(string $action): string
    {
        return app('url')->ajax($action);
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Return the csrf field.
     *
     * @return string
     */
    function csrf_field(): string
    {
        return wp_nonce_field('_token', '_token');
    }
}

if (!function_exists('dd')) {
    /**
     * Die and dump.
     *
     * @param mixed ...$args
     *
     * @return void
     */
    function dd(...$args): void
    {
        die(var_dump(...$args));
    }
}

if (!function_exists('dump')) {
    /**
     * Dump.
     *
     * @param mixed ...$args
     *
     * @return void
     */
    function dump(...$args): void
    {
        var_dump(...$args);
    }
}

if (!function_exists('event')) {
    /**
     * Dispatch an event and call the listeners.
     *
     * @param mixed $args

     * @return void
     */
    function event(...$args)
    {
        return app('events')->dispatch(...$args);
    }
}

if (!function_exists('method_field')) {
    /**
     * Return the method field.
     *
     * @param string $method The HTTP method
     *
     * @return void
     */
    function method_field(string $method): void
    {
        echo '<input type="hidden" name="_method" value="' . $method . '" />';
    }
}

if (!function_exists('request')) {
    /**
     * Return the request instance.
     *
     * @return \Genesis\Http\Request
     */
    function request(): Request
    {
        return app('request');
    }
}

if (!function_exists('url')) {
    /**
     * Return the url instance.
     *
     * @return \Genesis\Routing\URL|string
     */
    function url(string $path = '')
    {
        if ($path) {
            return app('url')->home($path);
        }
        return app('url');
    }
}

if (!function_exists('view')) {
    /**
     * Return a view.
     *
     * @param string  $view
     * @param array   $args
     *
     * @return string
     */
    function view(string $view, array $args = [])
    {
        return app('view')->make($view, $args);
    }
}

if (!function_exists('windows_os')) {
    /**
     * Determine whether the current environment is Windows based.
     *
     * @return bool
     */
    function windows_os()
    {
        return PHP_OS_FAMILY === 'Windows';
    }
}
