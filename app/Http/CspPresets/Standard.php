<?php

declare(strict_types=1);

namespace App\Http\CspPresets;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class Standard implements Preset
{
    #[\Override]
    public function configure(Policy $policy): void
    {
        $policy
            ->add(Directive::DEFAULT, Keyword::SELF)
            ->add(Directive::SCRIPT, URL::to('/build/').'/')
            ->add(Directive::IMG, Keyword::SELF)
            ->add(Directive::IMG, 'data:') // Bootstrap 4 -__-
            ->add(Directive::STYLE, URL::to('/build/').'/')
            ->add(Directive::CONNECT, Keyword::NONE)
            ->add(Directive::FRAME, Keyword::NONE)
            ->add(Directive::FONT, Keyword::NONE)
            ->add(Directive::FRAME_ANCESTORS, Keyword::NONE)
            ->add(Directive::BASE, Keyword::NONE)
            ->add(Directive::MANIFEST, Keyword::NONE)
            ->add(Directive::MEDIA, Keyword::NONE)
            ->add(Directive::OBJECT, Keyword::NONE)
            ->add(Directive::WORKER, Keyword::NONE);

        // To display the Discord avatar at navbar when logged in
        if (Auth::check()) {
            $policy->add(Directive::IMG, 'https://cdn.discordapp.com/avatars/');
        }

        if (config('ricochet.google_analytics_id')) {
            $this->addForGoogleAnalytics($policy);
        }

        if (config('debugbar.enabled') || App::hasDebugModeEnabled()) {
            $this->addForDebugbar($policy);
        }
    }

    /**
     * https://developers.google.com/tag-platform/security/guides/csp#google_analytics_4_google_analytics
     */
    private function addForGoogleAnalytics(Policy $policy): void
    {
        $policy
            ->add(Directive::SCRIPT, 'https://www.googletagmanager.com/gtag/js')
            ->add(Directive::IMG, 'https://*.google-analytics.com')
            ->add(Directive::IMG, 'https://*.googletagmanager.com')
            ->add(Directive::CONNECT, 'https://*.google-analytics.com')
            ->add(Directive::CONNECT, 'https://*.analytics.google.com')
            ->add(Directive::CONNECT, 'https://*.googletagmanager.com');
    }

    /**
     * For Laravel Debugbar
     */
    private function addForDebugbar(Policy $policy): void
    {
        $policy
            ->add(Directive::SCRIPT, URL::to('/_debugbar/').'/')
            ->add(Directive::SCRIPT, Keyword::UNSAFE_INLINE)
            ->add(Directive::STYLE, URL::to('/_debugbar/').'/')
            ->add(Directive::STYLE, Keyword::UNSAFE_INLINE)
            ->add(Directive::CONNECT, URL::to('/_debugbar/').'/')
            ->add(Directive::FONT, 'data:')
            ->add(Directive::IMG, 'data:');
    }

    /*
    public function addForIgnition(Policy $policy): void
    {
        $policy
            ->add(Directive::DEFAULT, Keyword::NONE)
            ->add(Directive::SCRIPT, Keyword::UNSAFE_INLINE)
            ->add(Directive::STYLE, Keyword::UNSAFE_INLINE)
            ->add(Directive::CONNECT, URL::to('/_ignition/').'/')
            ->add(Directive::IMG, 'data:');
    }
    */
}
