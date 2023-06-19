<?php

namespace Itiden\FA;

use Itiden\FA\Http\Controllers\FAController;
use Illuminate\Support\Facades\Route;
use Statamic\Statamic;
use Statamic\Providers\AddonServiceProvider;
use Itiden\FA\Widgets\FA;

class ServiceProvider extends AddonServiceProvider
{
    protected $viewNamespace = 'fa_widget';

    protected $widgets = [
        FA::class
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
        ]);
    }

    protected $vite = [
        'input' => [
            'resources/js/cp.js',
            'resources/js/components/widgets/fa.png'
        ],
        'publicDirectory' => '/vendor/statamic-fa-widget',
    ];
}
