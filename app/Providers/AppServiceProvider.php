<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
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

        Blade::directive('mixPath', static function (string $path) {
            return '<?php echo app(\App\Helpers\MixManifestWithIntegrity::class)::getPath('.$path.'); ?>';
        });

        Paginator::useBootstrapFour();

        RateLimiter::for('level-download', static function (Request $request) {
            return Limit::perSecond(2, 1)->by($request->ip());
        });

        RateLimiter::for('syncratings', static function (Request $request) {
            // The game will retry sending the ratings anyway
            return Limit::perSecond(1, 30)->by($request->ip());
        });
    }
}
