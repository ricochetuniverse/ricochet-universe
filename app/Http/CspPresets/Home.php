<?php

declare(strict_types=1);

namespace App\Http\CspPresets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;

class Home extends Standard
{
    #[\Override]
    public function configure(Policy $policy): void
    {
        parent::configure($policy);

        // For Discord server widget
        $policy
            ->add(Directive::IMG, 'https://cdn.discordapp.com/widget-avatars/')
            ->add(Directive::CONNECT, 'https://discordapp.com/api/guilds/295184393109110785/widget.json');
    }
}
