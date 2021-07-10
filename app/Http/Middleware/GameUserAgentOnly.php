<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GameUserAgentOnly
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function handle(Request $request, Closure $next)
    {
        $userAgent = $request->userAgent();
        if (strpos($userAgent, 'Ricochet ') === 0 || strpos($userAgent, 'Rebound ') === 0) {
            return $next($request);
        }

        throw new \Exception('This controller can only be called using a game user-agent.');
    }
}
