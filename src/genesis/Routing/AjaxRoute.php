<?php

namespace Genesis\Routing;

use Closure;
use Genesis\Routing\Router;

class AjaxRoute
{
    /**
     * Create the route instance
     *
     * @param string                  $action   The ajax action to lsiten for
     * @param \Closure                $callback The callback to run
     * @param \Genesis\Routing\Router $router   The router instance
     *
     * @return void
     */
    public function __construct(string $action, Closure $callback, Router $router)
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
        add_action("wp_ajax_nopriv_{$this->action}", [$this, 'handle']);
    }

    /**
     * Add the auth ajax action
     *
     * @return void
     */
    public function addAuthAction(): void
    {
        add_action("wp_ajax_{$this->action}", [$this, 'handle']);
    }

    /**
     * Handle the route call
     *
     * @return mixed
     */
    public function handle()
    {
        return call_user_func($this->callback, $this->router->request);
    }
}
