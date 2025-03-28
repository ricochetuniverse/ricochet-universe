<?php

declare(strict_types=1);

namespace App\Http\CspPresets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;

class Mods extends Standard
{
    #[\Override]
    public function configure(Policy $policy): void
    {
        parent::configure($policy);

        // For YouTube video player
        $policy
            ->add(Directive::FRAME, 'https://www.youtube.com/embed/');
    }
}
