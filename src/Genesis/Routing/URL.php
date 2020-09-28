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

    public function previous()
    {
        # code...
    }

    /**
     * Go to the login page.
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
     * Go to the logout page.
     *
     * @param string $redirect The redirect URL
     *
     * @return string
     */
    public function logout(string $redirect = '/'): string
    {
        return wp_logout_url($redirect);
    }
}
