<?php

namespace Genesis\Routing;

use Exception;
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
     * @return void
     */
    public function handle(): void
    {
        if (!app('csrf')->verify()) {
            die(http_response_code(403));
        }
        die(call_user_func($this->resolveCallback($this->callback), $this->router->request));
    }

    /**
     * Resolve the callback
     *
     * @param callable|string $callback
     * @return callable
     *
     * @throws \Exception
     */
    public function resolveCallback($callback): callable
    {
        if (is_callable($callback)) {
            return $callback;
        }
        if (class_exists($callback)) {
            return new $callback;
        }
        throw new Exception('The controller couldn\'t be resolved');
    }
}
