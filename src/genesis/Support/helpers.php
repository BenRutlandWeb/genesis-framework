<?php

use Genesis\Auth\Auth;
use Genesis\Foundation\Application;
use Genesis\Foundation\Mix;
use Genesis\Http\Request;
use Genesis\Http\Response;
use Illuminate\Contracts\View\Factory as ViewFactory;

if (!function_exists('abort')) {
    function abort(int $code, string $description = '')
    {
        die(status_header($code, $description));
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
    function ajax(string $action = ''): string
    {
        return app('url')->ajax($action);
    }
}

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

if (!function_exists('app_path')) {
    /**
     * Return the app path.
     *
     * @param string $path
     *
     * @return string
     */
    function app_path(string $path = ''): string
    {
        return app()->path($path);
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

if (!function_exists('base_path')) {
    /**
     * Return the base path.
     *
     * @param string $path
     *
     * @return string
     */
    function base_path(string $path = ''): string
    {
        return app()->basePath($path);
    }
}

if (!function_exists('config_path')) {
    /**
     * Return the config path.
     *
     * @param string $path
     *
     * @return string
     */
    function config_path(string $path = ''): string
    {
        return app()->configPath($path);
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
        return wp_nonce_field('_token', '_token', true, false);
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Return the csrf token.
     *
     * @return string
     */
    function csrf_token(): string
    {
        return wp_create_nonce('_token');
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

if (!function_exists('inline_svg')) {
    /**
     * Inline an SVG file.
     *
     * @param string $path
     *
     * @return string
     */
    function inline_svg(string $path): string
    {
        $path = app()->basePath("assets/svg/{$path}.svg");

        return app('files')->get($path);
    }
}

if (!function_exists('method_field')) {
    /**
     * Return the method field.
     *
     * @param string $method The HTTP method
     *
     * @return string
     */
    function method_field(string $method): string
    {
        return '<input type="hidden" name="_method" value="' . esc_attr($method) . '" />';
    }
}

if (!function_exists('mix')) {
    /**
     * Get the path to a versioned Mix file.
     *
     * @param  string  $path
     * @param  string  $manifestDirectory
     * @return \Illuminate\Support\HtmlString|string
     *
     * @throws \Exception
     */
    function mix($path)
    {
        return app(Mix::class)($path);
    }
}


if (!function_exists('public_path')) {
    /**
     * Return the resource path.
     *
     * @param string $path
     *
     * @return string
     */
    function public_path(string $path = ''): string
    {
        return app()->publicPath($path);
    }
}

if (!function_exists('request')) {
    /**
     * Get an instance of the current request or an input item from the request.
     *
     * @param  array|string|null $key
     * @param  mixed             $default
     *
     * @return \Genesis\Http\Request|string|array
     */
    function request($key = null, $default = null): Request
    {
        if (is_null($key)) {
            return app('request');
        }
        if (is_array($key)) {
            return app('request')->only($key);
        }
        return app('request')->$key ?? $default;
    }
}

if (!function_exists('resource_path')) {
    /**
     * Return the resource path.
     *
     * @param string $path
     *
     * @return string
     */
    function resource_path(string $path = ''): string
    {
        return app()->resourcePath($path);
    }
}

if (!function_exists('response')) {
    /**
     * Get an instance of the current request or an input item from the request.
     *
     * @param  string $content
     *
     * @return \Genesis\Http\Response
     */
    function response(string $content = ''): Response
    {
        if ($content) {
            return app('response')->content($content);
        }
        return app('response');
    }
}

if (!function_exists('storage_path')) {
    /**
     * Return the resource path.
     *
     * @param string $path
     *
     * @return string
     */
    function storage_path(string $path = ''): string
    {
        return app()->storagePath($path);
    }
}

if (!function_exists('url')) {
    /**
     * Return the url instance.
     *
     * @param string $path
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
     * Get the evaluated view contents for the given view.
     *
     * @param  string|null  $view
     * @param  \Illuminate\Contracts\Support\Arrayable|array  $data
     * @param  array  $mergeData
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    function view($view = null, $data = [], $mergeData = [])
    {
        $factory = app(ViewFactory::class);

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($view, $data, $mergeData);
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
