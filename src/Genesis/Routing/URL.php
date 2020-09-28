<?php

namespace Genesis\Routing;

class URL
{
    public function current()
    {
        # code...
    }

    public function full()
    {
        # code...
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
