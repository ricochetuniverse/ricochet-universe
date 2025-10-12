<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Helpers\Str;
use Tests\TestCase;

class StrHelperTest extends TestCase
{
    public function test_escape_like(): void
    {
        $this->assertEquals('hello', Str::escapeLike('hello'));
        $this->assertEquals('abc\%def', Str::escapeLike('abc%def'));
        $this->assertEquals('abc\_def', Str::escapeLike('abc_def'));
        $this->assertEquals('abc\%\_\\\\def', Str::escapeLike('abc%_\def'));
    }
}
