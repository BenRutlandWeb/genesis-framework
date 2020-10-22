<?php

namespace Genesis\Routing;

use Genesis\Http\Request;

class UrlGenerator
{
    /**
     * The request instance
     *
     * @var \Genesis\Http\Request
     */
    protected $request;

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
     * return the registration URL
     *
     * @return string
     */
    public function register(string $redirect = '/'): string
    {
        return add_query_arg('redirect_to', urlencode($redirect), wp_registration_url());
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

    /**
     * Return the ajax URL
     *
     * @param string $action The ajax action
     *
     * @return string
     */
    public function ajax(string $action = ''): string
    {
        if ($action) {
            return admin_url("admin-ajax.php?action={$action}");
        }
        return admin_url("admin-ajax.php");
    }

    /**
     * Redirect to another page, with an optional status code
     *
     * @param string  $url    The URL to redirect to
     * @param integer $status The status code to send
     *
     * @return void
     */
    public function redirect(string $url, int $status = 302): void
    {
        die(wp_redirect($url, $status));
    }

    /**
     * Return the admin URL
     *
     * @param string $path
     *
     * @return string
     */
    public function admin(string $path = ''): string
    {
        return admin_url($path);
    }
}
