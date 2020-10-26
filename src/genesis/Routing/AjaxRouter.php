<?php

namespace Genesis\Routing;

use Genesis\Routing\AjaxRoute;
use Genesis\Routing\Router;

class AjaxRouter extends Router
{
    /**
     * Listen for an ajax action
     *
     * @param string          $action
     * @param callable|string $callback
     *
     * @return \Genesis\Routing\AjaxRoute
     */
    public function listen(string $action, $callback): AjaxRoute
    {
        return $this->newRoute($action, $callback);
    }

    /**
     * Create a new route
     *
     * @param string          $action
     * @param callable|string $callback
     *
     * @return \Genesis\Routing\AjaxRoute
     */
    public function newRoute(string $action, $callback): AjaxRoute
    {
        return new AjaxRoute($action, $callback, $this, $this->app);
    }
}
