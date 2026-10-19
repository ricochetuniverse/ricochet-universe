<?php

declare(strict_types=1);

namespace Tests\Unit\Rules;

use App\Rules\ValidTimestamp;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ValidTimestampRuleTest extends TestCase
{
    public static function passingValuesProvider(): array
    {
        return [
            ['1596879035'],
            [1596879036],
            ['0'], // technically valid :/
            [0],
        ];
    }

    #[DataProvider('passingValuesProvider')]
    public function test_passing_values(mixed $value): void
    {
        $this->assertTrue(validator(
            ['input' => $value],
            ['input' => new ValidTimestamp]
        )->passes());
    }

    public static function failingValuesProvider(): array
    {
        return [
            ['abc'], // must be an integer
            [1596879037.5],
            [-1], // no underflowing
            [Carbon::now()->addMonths(13)], // no far future dates
        ];
    }

    #[DataProvider('failingValuesProvider')]
    public function test_failing_values(mixed $value): void
    {
        $this->assertFalse(validator(
            ['input' => $value],
            ['input' => new ValidTimestamp]
        )->passes());
    }
}
