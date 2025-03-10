<?php

namespace App\Http\Middleware;

use App\Helpers\GameUserAgent;
use Closure;
use Illuminate\Http\Request;

class GameUserAgentOnly
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

        throw new \Exception('This controller can only be called using a game user-agent.');
    }
}
