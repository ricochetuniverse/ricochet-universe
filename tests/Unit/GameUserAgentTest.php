<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Helpers\GameUserAgent;
use Illuminate\Http\Request;
use Tests\TestCase;

class GameUserAgentTest extends TestCase
{
    public function test_user_agent_string(): void
    {
        $this->assertFalse(GameUserAgent::check('nope'));

        // Windows
        $this->assertTrue(GameUserAgent::check('Ricochet Infinity Version 3 Build 62'));
        $this->assertTrue(GameUserAgent::check('Rebound Infinity Version 3 Build 68'));

        $this->assertTrue(GameUserAgent::check(rawurldecode('Ricochet Infinity Version 3 R%E9vision 71'))); // French
        $this->assertTrue(GameUserAgent::check(rawurldecode('Ricochet Infinity Versi%F3n 3 Construir 71'))); // Spanish

        // Mac
        $this->assertTrue(GameUserAgent::check('Ricochet Lost Worlds Version 3 Build 71'));
    }

    public function test_request(): void
    {
        $request = new Request;
        $this->assertFalse(GameUserAgent::checkRequest($request));

        $request = new Request(server: ['HTTP_USER_AGENT' => 'Ricochet Infinity Version 3 Build 62']);
        $this->assertTrue(GameUserAgent::checkRequest($request));
    }
}
