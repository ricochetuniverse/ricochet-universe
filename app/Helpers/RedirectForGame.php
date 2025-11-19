<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Uri;

abstract class RedirectForGame
{
    /**
     * Games with old libcurl.dll cannot use HTTPS, and the HTML response content must be blank
     */
    public static function to(Uri $url, ?Request $currentRequest = null): RedirectResponse
    {
        $isSecure = $currentRequest?->isSecure() ?? true;

        if ($currentRequest && ! $isSecure) {
            $url = $url->withScheme('http');

            // Adjust port for dev environment
            $url = $url->withPort($currentRequest->getPort());
        }

        $response = redirect((string) $url);

        // Old libcurl.dll would concat the content of the initial redirect due to HTTP keep-alive
        // This issue happens on production (Caddy), but not with the PHP dev server
        if (! $isSecure) {
            $response->setContent('');
        }

        return $response;
    }
}
