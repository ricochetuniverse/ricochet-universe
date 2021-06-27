<?php

namespace Tests\Unit;

use App\Rules\TagName;
use Tests\TestCase;

class TagNameRuleTest extends TestCase
{
    public function testRule(): void
    {
        $rule = new TagName;

        $this->assertTrue($rule->passes('', 'abc'));
        $this->assertFalse($rule->passes('', 'a,b'));
        $this->assertFalse($rule->passes('', 'a;b'));
    }
}
