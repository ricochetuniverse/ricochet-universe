<?php

namespace Tests\Unit;

use App\Rules\ValidTimestamp;
use Carbon\Carbon;
use Tests\TestCase;

class ValidTimestampRuleTest extends TestCase
{
    public function testRule(): void
    {
        $rule = new ValidTimestamp;

        $this->assertTrue($rule->passes('', '1596879035'));
        $this->assertTrue($rule->passes('', 1596879036));
        $this->assertTrue($rule->passes('', '0')); // technically valid :/
        $this->assertTrue($rule->passes('', 0));

        $this->assertFalse($rule->passes('', 'abc')); // must be an integer
        $this->assertFalse($rule->passes('', 1596879037.5));
        $this->assertFalse($rule->passes('', -1)); // no underflowing
        $this->assertFalse($rule->passes('', Carbon::now()->addMonths(13))); // no far future dates
    }
}
