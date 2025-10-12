<?php

namespace App\Console\Commands;

use App\Services\CatalogService;
use Illuminate\Console\Command;

class ClearCatalogCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ricochet:clear-catalog-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear cache for /gateway/catalog.php';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        CatalogService::clearCache();

        $this->info('Done.');
    }
}
