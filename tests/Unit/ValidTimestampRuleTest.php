<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Rules\ValidTimestamp;
use Carbon\Carbon;
use Tests\TestCase;

class ValidTimestampRuleTest extends TestCase
{
    public function test_passing_values(): void
    {
        $names = [
            '1596879035',
            1596879036,
            '0', // technically valid :/
            0,
        ];

        $rule = new ValidTimestamp;

        foreach ($names as $name) {
            $this->assertTrue(
                validator(['name' => $name], ['name' => $rule])->passes(),
                $name.' should pass'
            );
        }
    }

    public function test_failing_values(): void
    {
        $names = [
            'abc', // must be an integer
            1596879037.5,
            -1, // no underflowing
            Carbon::now()->addMonths(13), // no far future dates
        ];

        $rule = new ValidTimestamp;

        foreach ($names as $name) {
            $this->assertFalse(
                validator(['name' => $name], ['name' => $rule])->passes(),
                $name.' should fail'
            );
        }
    }
}
