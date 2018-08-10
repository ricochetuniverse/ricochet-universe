<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

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
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Cache::forget('level_catalog');
        Cache::forget('level_catalog_http');

        $this->info('Done.');
    }
}
