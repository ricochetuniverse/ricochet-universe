<?php

namespace App\Helpers;

use Illuminate\Http\RedirectResponse;

class RedirectForGame
{
    /**
     * Games with old libcurl.dll cannot use HTTPS, and the HTML response content must be blank
     */
    public static function to(bool $isSecure, string $url): RedirectResponse
    {
        if (! $isSecure) {
            $url = preg_replace('/^https:\/\//', 'http://', $url);
        }

        $response = redirect($url);

        // Old libcurl.dll would concat the content of the initial redirect
        // Note that this issue does not appear on the PHP dev server, maybe "Connection: close" header?
        if (! $isSecure) {
            $response->setContent('');
        }

        return $response;
    }
}
