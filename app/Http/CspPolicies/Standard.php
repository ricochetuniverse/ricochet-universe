<?php

namespace App\Http\CspPolicies;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policies\Policy;

class Standard extends Policy
{
    #[\Override]
    public function configure(): void
    {
        $this
            ->addDirective(Directive::DEFAULT, Keyword::SELF)
            ->addDirective(Directive::SCRIPT, URL::to('/build/').'/')
            ->addDirective(Directive::IMG, Keyword::SELF)
            ->addDirective(Directive::IMG, 'data:') // Bootstrap 4 -__-
            ->addDirective(Directive::IMG, 'https://web.archive.org/web/20171205000449im_/http://www.ricochetInfinity.com/levels/')
            ->addDirective(Directive::STYLE, URL::to('/build/').'/')
            ->addDirective(Directive::CONNECT, Keyword::NONE)
            ->addDirective(Directive::FRAME, Keyword::NONE)
            ->addDirective(Directive::FONT, Keyword::NONE)
            ->addDirective(Directive::FRAME_ANCESTORS, Keyword::NONE)
            ->addDirective(Directive::BASE, Keyword::NONE)
            ->addDirective(Directive::MANIFEST, Keyword::NONE)
            ->addDirective(Directive::MEDIA, Keyword::NONE)
            ->addDirective(Directive::OBJECT, Keyword::NONE)
            ->addDirective(Directive::WORKER, Keyword::NONE);

        // To display the Discord avatar at navbar when logged in
        if (Auth::check()) {
            $this->addDirective(Directive::IMG, 'https://cdn.discordapp.com/avatars/');
        }

        if (config('ricochet.google_analytics_id')) {
            $this->addForGoogleAnalytics();
        }

        if (config('debugbar.enabled') || App::hasDebugModeEnabled()) {
            $this->addForDebugbar();
        }
    }

    /**
     * https://developers.google.com/tag-platform/security/guides/csp#google_analytics_4_google_analytics
     */
    private function addForGoogleAnalytics(): void
    {
        $this
            ->addDirective(Directive::SCRIPT, 'https://www.googletagmanager.com/gtag/js')
            ->addDirective(Directive::IMG, 'https://*.google-analytics.com')
            ->addDirective(Directive::IMG, 'https://*.googletagmanager.com')
            ->addDirective(Directive::CONNECT, 'https://*.google-analytics.com')
            ->addDirective(Directive::CONNECT, 'https://*.analytics.google.com')
            ->addDirective(Directive::CONNECT, 'https://*.googletagmanager.com');
    }

    /**
     * For Laravel Debugbar
     */
    private function addForDebugbar(): void
    {
        $this
            ->addDirective(Directive::SCRIPT, URL::to('/_debugbar/').'/')
            ->addDirective(Directive::SCRIPT, Keyword::UNSAFE_INLINE)
            ->addDirective(Directive::STYLE, URL::to('/_debugbar/').'/')
            ->addDirective(Directive::STYLE, Keyword::UNSAFE_INLINE)
            ->addDirective(Directive::CONNECT, URL::to('/_debugbar/').'/')
            ->addDirective(Directive::FONT, 'data:')
            ->addDirective(Directive::IMG, 'data:');
    }

    /*
    public function addForIgnition(): void
    {
        $this
            ->addDirective(Directive::DEFAULT, Keyword::NONE)
            ->addDirective(Directive::SCRIPT, Keyword::UNSAFE_INLINE)
            ->addDirective(Directive::STYLE, Keyword::UNSAFE_INLINE)
            ->addDirective(Directive::CONNECT, URL::to('/_ignition/').'/')
            ->addDirective(Directive::IMG, 'data:');
    }
    */
}
