<?php

declare(strict_types=1);

namespace App\Console\Commands\Discord;

use App\Services\DiscordApi\ApiClient;
use Illuminate\Console\Command;

class RemoveDiscordInteractions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ricochet-discord:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all Discord interaction commands';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ApiClient::put('applications/'.config('services.discord.client_id').'/commands');

        $this->info('Removed all Discord interaction commands.');
    }
}
