<?php

namespace Genesis\Routing;

use Genesis\Http\Request;

class URL
{
    /**
     * Assign the request object to the instance.
     *
     * @param \Genesis\Http\Request $request
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get the current URL without query parameters.
     *
     * @return string
     */
    public function current(): string
    {
        return $this->request->url();
    }

    /**
     * Get the current URL including query parameters.
     *
     * @return string
     */
    public function full(): string
    {
        return $this->request->fullUrl();
    }

    /**
     * Get the URL for the previous request.
     *
     * @param string|null $fallback
     *
     * @return string
     */
    public function previous(?string $fallback = null): string
    {
        if ($previous = wp_get_referer()) {
            return $previous;
        }
        if ($fallback) {
            return $fallback;
        }
        return $this->home();
    }

    /**
     * Return the asset url.
     *
     * @param string $path
     * @param bool   $absolute
     *
     * @return string
     */
    public function asset(string $path, bool $absolute = true): string
    {
        return ($absolute ? get_template_directory_uri() : '') . '/assets/' . $path;
    }

    /**
     * return the login URL
     *
     * @param string $redirect
     *
     * @return string
     */
    public function login(string $redirect = '/'): string
    {
        return wp_login_url($redirect);
    }

    /**
     * Return the logout URL
     *
     * @param string $redirect The redirect URL
     *
     * @return string
     */
    public function logout(string $redirect = '/'): string
    {
        return wp_logout_url($redirect);
    }

    /**
     * Return the home URL
     *
     * @param string $path The path to append to the home URL
     *
     * @return string
     */
    public function home(string $path = ''): string
    {
        return home_url($path);
    }
}
