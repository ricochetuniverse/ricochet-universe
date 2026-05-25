<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\CatalogService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

#[Signature('ricochet:cache-catalog')]
#[Description('Cache the catalog for /gateway/catalog.php')]
class CacheCatalog extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Cache::flexible(CatalogService::CACHE_KEY_HTTPS, [0, 60 * 60 * 24], static function () {
            return CatalogService::getCatalog(true);
        });

        Cache::flexible(CatalogService::CACHE_KEY_HTTP, [0, 60 * 60 * 24], static function () {
            return CatalogService::getCatalog(false);
        });

        $this->info('Catalog cached.');
    }
}
