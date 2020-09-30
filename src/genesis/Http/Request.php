<?php

namespace Genesis\Http;

use Illuminate\Support\Collection;
use JsonSerializable;

class Request implements JsonSerializable
{
    public function __construct($query = [], $post = [], $files = [], $cookies = [], $server = [], $headers = [], $content = null)
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

    public function path()
    {
        return strtok($this->server->get('REQUEST_URI'), '?');
    }

    public function root()
    {
        return home_url();
    }

    public function url()
    {
        return home_url(strtok($this->server->get('REQUEST_URI'), '?'));
    }

    public function fullUrl()
    {
        return home_url($this->server->get('REQUEST_URI'));
    }

    public function getScheme()
    {
        return $this->isSecure() ? 'https' : 'http';
    }

    public function isSecure()
    {
        return is_ssl();
    }

    public function ip()
    {
        return $this->ips()[0];
    }

    public function ips()
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

    public function method()
    {
        $realMethod = $this->getRealMethod();

        if ($realMethod !== 'POST') {
            return $realMethod;
        }

        $method = strtoupper(
            $this->headers->get(
                'X-HTTP-METHOD-OVERRIDE',
                $this->request->get('_method', 'POST')
            )
        );

        if (in_array($method, ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'PATCH', 'PURGE', 'TRACE'], true)) {
            return $method;
        }
        return $realMethod;
    }

    public function getRealMethod()
    {
        return strtoupper($this->server->get('REQUEST_METHOD', 'GET'));
    }

    public function isMethod(string $method)
    {
        return $this->method() === strtoupper($method);
    }

    public function userAgent()
    {
        return $this->headers->get('User-Agent');
    }

    public function input(?string $key = null, $default = null)
    {
        if ($key) {
            return $this->request->get($key) ?? $default;
        }
        return $this->request->all();
    }

    public function query(?string $key = null, $default = null)
    {
        if ($key) {
            return $this->query->get($key) ?? $default;
        }
        return $this->query->all();
    }

    public function header(?string $key = null, $default = null)
    {
        if ($key) {
            return $this->headers->get($key) ?? $default;
        }
        return $this->headers->all();
    }

    public function boolean(string $key, bool $default = false)
    {
        return filter_var($this->request->get($key, $default), FILTER_VALIDATE_BOOLEAN);
    }

    public function __call(string $method, array $args)
    {
        return $this->request->$method(...$args);
    }

    public function __get(string $key)
    {
        return $this->request[$key] ?? null;
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
}
