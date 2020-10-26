<?php

namespace Genesis\Routing;

use Genesis\Routing\Router;

class AjaxRoute
{
    /**
     * Create the route instance
     *
     * @param string                  $action   The ajax action to lsiten for
     * @param callble|string          $callback The callback to run
     * @param \Genesis\Routing\Router $router   The router instance
     *
     * @return void
     */
    public function __construct(string $action, $callback, Router $router)
    {
        $this->action = $action;
        $this->callback = $callback;
        $this->router = $router;

        $this->register();
    }

    /**
     * Check the user auth and create the ajax actions
     *
     * @return void
     */
    public function register(): void
    {
        $guest = $this->router->hasMiddleware('guest');
        $auth = $this->router->hasMiddleware('auth');
        $noMiddleware = !$guest && !$auth;

        if ($guest || $noMiddleware) {
            $this->addGuestAction();
        }
        if ($auth || $noMiddleware) {
            $this->addAuthAction();
        }
    }

    /**
     * Add the guest ajax action
     *
     * @return void
     */
    public function addGuestAction(): void
    {
        add_action("wp_ajax_nopriv_{$this->resolveURL()}", [$this, 'handle']);
    }

    /**
     * Add the auth ajax action
     *
     * @return void
     */
    public function addAuthAction(): void
    {
        add_action("wp_ajax_{$this->resolveURL()}", [$this, 'handle']);
    }

    /**
     * Resolve the route URL
     *
     * @return string
     */
    protected function resolveURL(): string
    {
        return $this->router->getPrefix($this->action, '_');
    }

    /**
     * Handle the route call
     *
     * @return void
     */
    public function handle(): void
    {
        if (!app('csrf')->verify()) {
            die(http_response_code(403));
        }
        die(app()->call($this->callback));
    }
}
