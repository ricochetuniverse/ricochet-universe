<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // https://planetscale.com/blog/laravels-safety-mechanisms
        Model::preventLazyLoading(! $this->app->isProduction());
        Model::preventAccessingMissingAttributes();
        Model::preventSilentlyDiscardingAttributes();

        Paginator::useBootstrapFour();
    }
}
