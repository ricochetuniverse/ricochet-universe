<?php

declare(strict_types=1);

namespace App\Console\Commands\Discord;

use App\Services\DiscordApi\ApiClient;
use App\Services\DiscordApi\Enums\ApplicationCommandType;
use App\Services\DiscordApi\InteractionNames;
use Illuminate\Console\Command;

class InitDiscordInteractions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ricochet-discord:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up the Discord interaction commands';

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
