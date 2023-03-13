<?php

namespace App\Http\Middleware;

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
        $userAgent = $request->userAgent();
        if (str_starts_with($userAgent, 'Ricochet ') || str_starts_with($userAgent, 'Rebound ')) {
            return $next($request);
        }

        throw new \Exception('This controller can only be called using a game user-agent.');
    }
}
