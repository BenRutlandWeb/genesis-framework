<?php

namespace Genesis\Foundation\Http\Middleware;

use Closure;
use Genesis\Http\Request;

class VerifyCsrfToken
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [];

    /**
     * Handle an incoming request.
     *
     * @param \Genesis\Http\Request $request
     * @param \Closure              $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (
            $this->isReading($request) ||
            $this->inExceptArray($request) ||
            $this->tokensMatch($request)
        ) {
            return $next($request);
        }
        abort(403, 'CSRF token mismatch.');
    }

    /**
     * Check if the HTTP request uses a "read" verb.
     *
     * @return bool
     */
    public function isReading(Request $request): bool
    {
        return in_array($request->method(), ['HEAD', 'GET', 'OPTIONS']);
    }

    /**
     * Determine if the request has a URI that should pass through CSRF verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function inExceptArray(Request $request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if (
                $except === $request->input('action') ||
                $request->fullUrlIs($except) ||
                $request->is($except)
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Verify the CSRF tokens match.
     *
     * @return bool
     */
    public function tokensMatch(Request $request): bool
    {
        return (bool) wp_verify_nonce($this->getTokenFromRequest($request), '_token');
    }

    /**
     * Get the CSRF token from the request.
     *
     * @return string
     */
    public function getTokenFromRequest(Request $request): string
    {
        return $request->input('_token') ?: $request->header('X-Csrf-Token') ?: '';
    }
}
