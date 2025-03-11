<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Helpers\GameUserAgent;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class DenyGameUserAgent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     *
     * @throws \Exception
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (GameUserAgent::checkRequest($request)) {
            throw new AccessDeniedHttpException('This controller cannot be accessed using a game user-agent.');
        }

        return $next($request);
    }
}
