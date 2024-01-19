<?php

namespace Itiden\FA;

use Illuminate\Support\Facades\Route;
use Itiden\FA\Http\Controllers\FAController;
use Itiden\FA\Widgets\FA;
use Statamic\Providers\AddonServiceProvider;
use Statamic\Statamic;

class ServiceProvider extends AddonServiceProvider
{
    protected $viewNamespace = 'fa_widget';

    protected $widgets = [
        FA::class,
    ];

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function bootAddon()
    {
        Statamic::pushCpRoutes(function () {
            Route::get('/fa', [FAController::class, 'index'])->name('fa');
        });

        $this->publishes([
            __DIR__ . '/../config/fa.php' => config_path('fa.php'),
        ], 'fa-widget-config');
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/fa.php',
            'fa'
        );
    }

    protected $vite = [
        'input' => [
            'resources/js/cp.js',
        ],
        'publicDirectory' => 'resources/dist',
    ];
}
