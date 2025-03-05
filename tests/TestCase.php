<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Http;

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
    }
}
