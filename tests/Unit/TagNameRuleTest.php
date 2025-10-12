<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Rules\TagName;
use Tests\TestCase;

class TagNameRuleTest extends TestCase
{
    public function test_passing_values(): void
    {
        $names = [
            'abc',
        ];

        $rule = new TagName;

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
            'a,b',
            'a;b',
        ];

        $rule = new TagName;

        foreach ($names as $name) {
            $this->assertFalse(
                validator(['name' => $name], ['name' => $rule])->passes(),
                $name.' should fail'
            );
        }
    }
}
