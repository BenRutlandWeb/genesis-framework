<?php

use Genesis\Auth\Auth;
use Genesis\Foundation\Application;
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

if (!function_exists('url')) {
    /**
     * Return the url instance.
     *
     * @return \Genesis\Routing\URL
     */
    function url(): URL
    {
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
