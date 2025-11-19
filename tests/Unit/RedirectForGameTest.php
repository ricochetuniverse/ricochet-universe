<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Helpers\RedirectForGame;
use Illuminate\Http\Request;
use Illuminate\Support\Uri;
use Tests\TestCase;

class RedirectForGameTest extends TestCase
{
    public function test_with_https(): void
    {
        $currentRequest = Request::create('https://example.com/old');

        $redirect = RedirectForGame::to(new Uri('https://example.com/new'), $currentRequest);
        $this->assertEquals('https://example.com/new', $redirect->getTargetUrl());
    }

    public function test_without_https(): void
    {
        $request = Request::create('http://example.com/old');

        $redirect = RedirectForGame::to(new Uri('https://example.com/new'), $request);
        $this->assertEquals('http://example.com/new', $redirect->getTargetUrl());
        $this->assertEmpty($redirect->getContent());
    }

    public function test_without_https_different_port(): void
    {
        $request = Request::create('http://example.com:8000/old');

        $redirect = RedirectForGame::to(new Uri('https://example.com/new'), $request);
        $this->assertEquals('http://example.com:8000/new', $redirect->getTargetUrl());
    }
}
