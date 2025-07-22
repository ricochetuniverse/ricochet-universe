<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
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

        RateLimiter::for('syncratings', function (Request $request) {
            // The game will retry sending the ratings anyway
            return Limit::perSecond(1, 30)->by($request->ip());
        });
    }
}
