<?php

namespace App\Console\Commands;

use App\Services\CatalogService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('ricochet:clear-catalog-cache')]
#[Description('Clear cache for /gateway/catalog.php')]
class ClearCatalogCache extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        CatalogService::clearCache();

        $this->info('Done.');
    }
}
