<?php

namespace Itiden\StatamicFathomAnalytics;

use App\Http\Controllers\FathomAnalyticsController;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Statamic\Statamic;
use Statamic\Providers\AddonServiceProvider;

class RouteServiceProvider extends AddonServiceProvider
{

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        Statamic::pushCpRoutes(function () {
            Route::get('/fathom-analytics', [FathomAnalyticsController::class, 'index'])->name('fathom-analytics');
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
