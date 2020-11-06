<?php

namespace Genesis\Http;

use Illuminate\Http\Request as IlluminateRequest;
use JsonSerializable;

class Request extends IlluminateRequest implements JsonSerializable
{
    /**
     * Compare the request path with the given path for a match
     *
     * @param string $path
     *
     * @return boolean
     */
    public function isPath(string $path): bool
    {
        return trim($path, '/') === trim($this->path(), '/');
    }

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
