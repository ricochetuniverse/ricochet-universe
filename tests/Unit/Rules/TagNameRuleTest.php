<?php

declare(strict_types=1);

namespace Tests\Unit\Rules;

use App\Rules\TagName;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class TagNameRuleTest extends TestCase
{
    public static function passingValuesProvider(): array
    {
        return [
            ['abc'],
        ];
    }

    #[DataProvider('passingValuesProvider')]
    public function test_passing_values(string $name): void
    {
        $this->assertTrue(validator(
            ['input' => $name],
            ['input' => new TagName]
        )->passes());
    }

    public static function failingValuesProvider(): array
    {
        return [
            ['a,b'],
            ['a;b'],
        ];
    }

    #[DataProvider('failingValuesProvider')]
    public function test_failing_values(string $name): void
    {
        $this->assertFalse(validator(
            ['input' => $name],
            ['input' => new TagName]
        )->passes());
    }
}
