<?php

namespace App\Http\CspPolicies;

use Spatie\Csp\Directive;

class Mods extends Standard
{
    #[\Override]
    public function configure(): void
    {
        parent::configure();

        // For YouTube video player
        $this
            ->addDirective(Directive::FRAME, 'https://www.youtube.com/embed/');
    }
}
