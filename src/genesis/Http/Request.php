<?php

namespace Genesis\Http;

use ArrayAccess;
use JsonSerializable;
use Illuminate\Support\Collection;

class Request implements ArrayAccess, JsonSerializable
{
    /**
     * The request query arguments
     *
     * @var \Illuminate\Support\Collection
     */
    protected $query;

    /**
     * The request form data
     *
     * @var \Illuminate\Support\Collection
     */
    protected $post;

    /**
     * The request query arguments and form data
     *
     * @var \Illuminate\Support\Collection
     */
    protected $request;

    /**
     * The request files
     *
     * @var \Illuminate\Support\Collection
     */
    protected $files;

    /**
     * The request cookies
     *
     * @var \Illuminate\Support\Collection
     */
    protected $cookies;

    /**
     * The server properties
     *
     * @var \Illuminate\Support\Collection
     */
    protected $server;

    /**
     * The request headers
     *
     * @var \Illuminate\Support\Collection
     */
    protected $headers;

    /**
     * The request content
     *
     * @var string
     */
    protected $content;

    /**
     * Collect each of the superglobals passed to the constructor
     *
     * @param array $query
     * @param array $post
     * @param array $files
     * @param array $cookies
     * @param array $server
     * @param array $headers
     * @param string $content
     *
     * @return void
     */
    public function __construct(array $query = [], array $post = [], array $files = [], array $cookies = [], array $server = [], array $headers = [], string $content = null)
    {
        $this->query = collect($query);
        $this->post = collect($post);
        $this->request = $this->post->merge($this->query);
        $this->files = collect($files);
        $this->cookies = collect($cookies);
        $this->server = collect($server);
        $this->headers = collect($headers);
        $this->content = $content;
    }

    /**
     * Get the request path
     *
     * @return string
     */
    public function path(): string
    {
        return strtok($this->server->get('REQUEST_URI'), '?');
    }

    /**
     * Get the root domain
     *
     * @return string
     */
    public function root(): string
    {
        return home_url();
    }

    /**
     * Get the URL without the query parameters
     *
     * @return string
     */
    public function url(): string
    {
        return home_url(strtok($this->server->get('REQUEST_URI'), '?'));
    }

    /**
     * Get the full URL including query parameters
     *
     * @return string
     */
    public function fullUrl(): string
    {
        return home_url($this->server->get('REQUEST_URI'));
    }

    /**
     * Get the scheme
     *
     * @return string
     */
    public function getScheme(): string
    {
        return $this->isSecure() ? 'https' : 'http';
    }

    /**
     * Check if the request is using https
     *
     * @return boolean
     */
    public function isSecure(): bool
    {
        return is_ssl();
    }

    /**
     * Get the most likely IP address
     *
     * @return string
     */
    public function ip(): string
    {
        return $this->ips()[0];
    }

    /**
     * Get an array of possible IPs
     *
     * @return array
     */
    public function ips(): array
    {
        $ips = [];
        if (($ip = $this->server->get('HTTP_CLIENT_IP')) && $this->validateIp($ip)) {
            $ips[] = $ip;
        }
        if ($header = $this->server->get('HTTP_X_FORWARDED_FOR')) {
            $iplist = explode(',', $header);
            foreach ($iplist as $ip) {
                if ($this->validateIp($ip)) {
                    $ips[] = $ip;
                }
            }
        }
        if (($ip = $this->server->get('HTTP_X_FORWARDED')) && $this->validateIp($ip)) {
            $ips[] = $ip;
        }
        if (($ip = $this->server->get('HTTP_X_CLUSTER_CLIENT_IP')) && $this->validateIp($ip)) {
            $ips[] = $ip;
        }
        if (($ip = $this->server->get('HTTP_FORWARDED_FOR')) && $this->validateIp($ip)) {
            $ips[] = $ip;
        }
        if (($ip = $this->server->get('HTTP_FORWARDED')) && $this->validateIp($ip)) {
            $ips[] = $ip;
        }
        if (($ip = $this->server->get('HTTP_X_REAL_IP')) && $this->validateIp($ip)) {
            $ips[] = $ip;
        }
        if (($ip = $this->server->get('REMOTE_ADDR')) && $this->validateIp($ip)) {
            $ips[] = $ip;
        }

        return array_unique($ips);
    }

    /**
     * Ensures an IP address is both a valid IP address and does not fall within
     * a private network range.
     *
     * @param string $ip
     *
     * @return bool
     */
    protected function validateIp(string $ip): bool
    {
        return filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6
        ) !== false;
    }

    /**
     * Get the method of the request, or the override method
     *
     * @return string
     */
    public function method(): string
    {
        $realMethod = $this->getRealMethod();

        if ($realMethod !== 'POST') {
            return $realMethod;
        }

        $method = strtoupper(
            $this->headers->get(
                'X-HTTP-METHOD-OVERRIDE',
                $this->input('_method', 'POST')
            )
        );

        if (in_array($method, ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'PATCH', 'PURGE', 'TRACE'], true)) {
            return $method;
        }
        return $realMethod;
    }

    /**
     * Get the real method of the request.
     *
     * @return string
     */
    public function getRealMethod(): string
    {
        return strtoupper($this->server->get('REQUEST_METHOD', 'GET'));
    }

    /**
     * Check if the methods match.
     *
     * @param string $method
     *
     * @return bool
     */
    public function isMethod(string $method): bool
    {
        return $this->method() === strtoupper($method);
    }

    /**
     * Get the User Agent
     *
     * @return string
     */
    public function userAgent(): string
    {
        return $this->headers->get('User-Agent');
    }

    /**
     * Get a request by the key.
     *
     * @param string|null $key
     * @param mixed       $default
     *
     * @return mixed
     */
    public function input(?string $key = null, $default = null)
    {
        if ($key) {
            return $this->request->get($key) ?? $default;
        }
        return $this->request->all();
    }

    /**
     * Get a query parameter by the key.
     *
     * @param string|null $key
     * @param mixed       $default
     *
     * @return mixed
     */
    public function query(?string $key = null, $default = null)
    {
        if ($key) {
            return $this->query->get($key) ?? $default;
        }
        return $this->query->all();
    }

    /**
     * Get a header by the key.
     *
     * @param string|null $key
     * @param mixed       $default
     *
     * @return mixed
     */
    public function header(?string $key = null, $default = null)
    {
        if ($key) {
            return $this->headers->get($key) ?? $default;
        }
        return $this->headers->all();
    }

    /**
     * Retrieve input as a boolean value.
     *
     * Returns true when value is "1", "true", "on", and "yes". Otherwise, returns false.
     *
     * @param string $key
     * @param bool   $default
     *
     * @return bool
     */
    public function boolean(string $key, bool $default = false): bool
    {
        return filter_var($this->input($key, $default), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Forward method calls to the request collection
     *
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     */
    public function __call(string $method, array $args)
    {
        return $this->request->$method(...$args);
    }

    /**
     * Dynamically get properties from the request collection
     *
     * @param string $key
     *
     * @return mixed|null
     */
    public function __get(string $key)
    {
        return $this->request[$key] ?? null;
    }


    /**
     * Determine if a given offset exists.
     *
     * @param  string  $key
     * @return bool
     */
    public function offsetExists($key): bool
    {
        return isset($this->request[$key]);
    }

    /**
     * Get the value at a given offset.
     *
     * @param  string  $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->request[$key] ?? null;
    }

    /**
     * Set the value at a given offset.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function offsetSet($key, $value): void
    {
        $this->request[$key] = $value;
    }

    /**
     * Unset the value at a given offset.
     *
     * @param string $key
     *
     * @return void
     */
    public function offsetUnset($key): void
    {
        unset($this->query[$key], $this->post[$key], $this->request[$key]);
    }

    /**
     * Only serialize the request object.
     *
     * @return \Illuminate\Support\Collection
     */
    public function jsonSerialize(): Collection
    {
        return $this->request;
    }

    /**
     * JSON eoncode the request
     *
     * @return string
     */
    public function __toString(): string
    {
        return json_encode($this->request);
    }
}
