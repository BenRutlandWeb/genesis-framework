<?php

namespace Genesis\Routing;

use Genesis\Http\Request;
use Illuminate\Routing\Route;
use WP_REST_Request;

class RestRoute extends Route
{

    /**
     * Dispatch the route
     *
     * @return $this
     */
    public function dispatch()
    {
        return $this->router->dispatchRestRoute($this);
    }

    /**
     * Bind the WP_REST_Request url parameters to the request and to the route.
     *
     * @param \WP_REST_Request $wpRequest
     * @param \Genesis\Http\Request $request
     * @return $this
     */
    public function bindRestParameters(WP_REST_Request $wpRequest, Request $request)
    {
        $parameters = $wpRequest->get_url_params();

        $request->merge($parameters);

        $this->originalParameters = $this->parameters = $parameters;

        return $this;
    }
}
