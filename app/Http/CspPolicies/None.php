<?php

namespace App\Http\CspPolicies;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policies\Policy;

class None extends Policy
{
    #[\Override]
    public function configure(): void
    {
        $this->addDirective(Directive::DEFAULT, Keyword::NONE);
    }
}
