<?php

namespace Tests;

use App\Helpers\MixManifestWithIntegrity;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\HtmlString;

abstract class TestCase extends BaseTestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // https://laravel-news.com/laravel-http-client-tips#content-preventing-stray-requests-in-tests
        Http::preventStrayRequests();

        $this->swap(MixManifestWithIntegrity::class, new class extends MixManifestWithIntegrity
        {
            public static function getPath(string $path): HtmlString
            {
                return new HtmlString('');
            }

            public static function getIntegrity(string $path): HtmlString
            {
                return new HtmlString('');
            }
        });
    }
}
