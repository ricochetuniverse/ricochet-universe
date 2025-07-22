<?php

namespace App\Http\Middleware;

use App\Helpers\GameUserAgent;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AllowOnlyGameUserAgent
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function handle(Request $request, Closure $next)
    {
        if (GameUserAgent::checkRequest($request)) {
            return $next($request);
        }

        throw new AccessDeniedHttpException('This page can only be accessed using a game user-agent.');
    }
}
