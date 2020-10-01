<?php

namespace Genesis\Routing;

use Closure;
use Genesis\Routing\AjaxRoute;
use Genesis\Routing\Router;

class AjaxRouter extends Router
{
    /**
     * Listen for an ajax action
     *
     * @param string   $action
     * @param \Closure $callback
     *
     * @return \Genesis\Routing\AjaxRoute
     */
    public function listen(string $action, Closure $callback): AjaxRoute
    {
        return new AjaxRoute($action, $callback, $this);
    }
}
