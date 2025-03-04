<?php

namespace App\Http\CspPolicies;

use Spatie\Csp\Directive;

class Home extends Standard
{
    #[\Override]
    public function configure(): void
    {
        parent::configure();

        // For Discord server widget
        $this
            ->addDirective(Directive::IMG, 'https://cdn.discordapp.com/widget-avatars/')
            ->addDirective(Directive::CONNECT, 'https://discordapp.com/api/guilds/295184393109110785/widget.json');
    }
}
