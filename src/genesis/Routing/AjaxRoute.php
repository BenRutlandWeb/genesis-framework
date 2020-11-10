<?php

namespace Genesis\Routing;

use Illuminate\Routing\Route;

class AjaxRoute extends Route
{
    /**
     * Fill the parameters property so the route can be bound.
     *
     * @var array
     */
    public $parameters = [];

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
