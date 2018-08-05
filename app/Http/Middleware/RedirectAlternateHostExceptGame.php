<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectAlternateHostExceptGame
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (strpos($request->header('User-Agent'), 'Ricochet Infinity ') !== 0) {
            if ($request->root() === 'http://www.ricochetinfinity.com') {
                return redirect($this->buildUrl($request->getUri()));
            }
        }

        return $next($request);
    }

    private function buildUrl($fullUrl)
    {
        $parsedUrl = parse_url($fullUrl);

        $finalUrl = config('app.url');
        $finalUrl .= $parsedUrl['path'];
        $finalUrl .= isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '';

        return $finalUrl;
    }
}
