<?php

namespace Genesis\Routing;

use Illuminate\Routing\Route;

class AjaxRoute extends Route
{
    /**
     * Dispatch the route
     *
     * @return $this
     */
    public function dispatch()
    {
        return $this->router->dispatchAjaxRoute($this);
    }
}
