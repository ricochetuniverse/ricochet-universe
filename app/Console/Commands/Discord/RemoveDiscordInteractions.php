<?php

declare(strict_types=1);

namespace App\Console\Commands\Discord;

use App\Services\DiscordApi\ApiClient;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('ricochet-discord:remove')]
#[Description('Remove all Discord interaction commands')]
class RemoveDiscordInteractions extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        ApiClient::put('applications/'.config('services.discord.client_id').'/commands');

        $this->info('Removed all Discord interaction commands.');
    }
}
