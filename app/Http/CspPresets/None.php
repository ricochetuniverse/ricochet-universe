<?php

declare(strict_types=1);

namespace App\Http\CspPresets;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class None implements Preset
{
    #[\Override]
    public function configure(Policy $policy): void
    {
        $policy->add(Directive::DEFAULT, Keyword::NONE);
    }
}
