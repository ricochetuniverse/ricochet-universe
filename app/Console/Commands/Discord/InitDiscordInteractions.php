<?php

declare(strict_types=1);

namespace App\Console\Commands\Discord;

use App\Services\DiscordApi\ApiClient;
use App\Services\DiscordApi\Enums\ApplicationCommandType;
use App\Services\DiscordApi\InteractionNames;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('ricochet-discord:init')]
#[Description('Set up the Discord interaction commands')]
class InitDiscordInteractions extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        ApiClient::put('applications/'.config('services.discord.client_id').'/commands',
            [
                [
                    'name' => InteractionNames::LEVEL_SET_INFO,
                    'type' => ApplicationCommandType::MESSAGE,
                ],
                [
                    'name' => InteractionNames::EXPORT_LEVEL_SET,
                    'type' => ApplicationCommandType::MESSAGE,
                ],
            ]
        );

        $this->info('Discord interaction commands set up.');
    }
}
