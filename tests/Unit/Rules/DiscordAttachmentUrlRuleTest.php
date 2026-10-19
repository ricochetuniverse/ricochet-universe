<?php

declare(strict_types=1);

namespace Tests\Unit\Rules;

use App\Rules\DiscordAttachmentUrl;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class DiscordAttachmentUrlRuleTest extends TestCase
{
    public static function passingValuesProvider(): array
    {
        return [
            ['https://cdn.discordapp.com/attachments/123/123/test.txt'],
        ];
    }

    #[DataProvider('passingValuesProvider')]
    public function test_passing_values(string $url): void
    {
        $this->assertTrue(validator(
            ['input' => $url],
            ['input' => new DiscordAttachmentUrl]
        )->passes());
    }

    public static function failingValuesProvider(): array
    {
        return [
            [''],
            ['http://nope'],
            ['https://cdn.discordapp.com/'],
            ['http://cdn.discordapp.com/attachments/123/123/test.txt'],
            ['https://nopecdn.discordapp.com/attachments/123/123/test.txt'],
            ['https://example.com/?https://cdn.discordapp.com/attachments/123/123/test.txt'],
            [123],
        ];
    }

    #[DataProvider('failingValuesProvider')]
    public function test_failing_values(mixed $url): void
    {
        $this->assertFalse(validator(
            ['input' => $url],
            ['input' => new DiscordAttachmentUrl]
        )->passes());
    }
}
