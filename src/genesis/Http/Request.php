<?php

namespace Genesis\Http;

use Illuminate\Http\Request as IlluminateRequest;
use JsonSerializable;

class Request extends IlluminateRequest implements JsonSerializable
{
    /**
     * Convert the request into a json string
     *
     * @return string
     */
    public function __toString(): string
    {
        return json_encode($this->all());
    }

    /**
     * Only serialize the parameter bag
     *
     * @return void
     */
    public function jsonSerialize(): array
    {
        return $this->all();
    }
}
